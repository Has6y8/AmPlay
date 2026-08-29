from flask import Flask, request, jsonify, Response
import cloudscraper
from bs4 import BeautifulSoup
import re
import subprocess
from urllib.parse import quote, urlparse, urlencode

app = Flask(__name__)

BASE_URL = "https://anidb.app"

session = cloudscraper.create_scraper(
    browser={'browser': 'chrome', 'platform': 'windows', 'desktop': True}
)
session.headers.update({
    "Referer": BASE_URL,
    "Accept": "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8"
})

cache_search = {}
cache_episodes = {}
cache_embed = {}
cache_languages = {}
cache_stream = {}
cache_info = {}

GENRE_MAP = {
    "action": "1", "drama": "2", "adventure": "3", "fantasy": "4", "comedy": "5",
    "sci-fi": "6", "mystery": "7", "gourmet": "8", "slice of life": "9", "supernatural": "10",
    "sports": "11", "award winning": "12", "ecchi": "13", "romance": "14", "boys love": "16",
    "erotica": "17", "suspense": "18", "avant garde": "19", "girls love": "20", "horror": "21"
}

def browse_anime(params):
    query_params = {}
    if params.get('q'): 
        # Tambahkan parameter 'keyword' karena CMS anime biasanya sangat responsif dengan ini
        query_params['q'] = params['q']
        query_params['keyword'] = params['q'] 
        
    if params.get('type'): query_params['type'] = params['type']
    if params.get('page'): query_params['page'] = params['page']
    
    genre_val = params.get('genres', '').lower()
    if genre_val: query_params['genres'] = GENRE_MAP.get(genre_val, genre_val)
        
    key_str = urlencode(query_params)
    if key_str in cache_search: 
        return cache_search[key_str]
        
    search_url = f"{BASE_URL}/browse?{key_str}"
    print(f"[Scrape] Browse: {search_url}")
    
    try:
        res = session.get(search_url)
        res.raise_for_status()
        soup = BeautifulSoup(res.text, "html.parser")
    except Exception as e:
        print(f"Error browse: {e}")
        return []
    
    results = []
    for card in soup.find_all("a", class_="anime-card"):
        href = card.get("href")
        title = card.get("title")
        if href and title:
            match = re.search(r'-(\d+)$', href)
            if match:
                img = card.find("img")
                poster_url = img.get("src") if img else None
                results.append({
                    "judul": title,
                    "url": href,
                    "id": match.group(1),
                    "poster_url": poster_url
                })
    cache_search[key_str] = results
    return results

def get_episodes(anime_id):
    if anime_id in cache_episodes:
        return cache_episodes[anime_id]
    print(f"[Scrape] Episodes for anime {anime_id}")
    api_url = f"{BASE_URL}/api/frontend/anime/{anime_id}/episodes"
    try:
        res = session.get(api_url)
        res.raise_for_status()
        data = res.json()
        episodes = data.get("episodes", [])
        episodes = sorted(episodes, key=lambda x: x.get("number", 0))
        cache_episodes[anime_id] = episodes
        return episodes
    except Exception as e:
        print(f"Error episodes: {e}")
        return []

def get_languages(episode_id):
    if episode_id in cache_languages:
        return cache_languages[episode_id]
    print(f"[Scrape] Languages for episode {episode_id}")
    api_url = f"{BASE_URL}/api/frontend/episode/{episode_id}/languages"
    try:
        res = session.get(api_url)
        res.raise_for_status()
        data = res.json()
        languages = data.get("languages", [])
        cache_languages[episode_id] = languages
        return languages
    except Exception as e:
        print(f"Error languages: {e}")
        return []

def get_embed_url(episode_id, preferred='sub'):
    langs = get_languages(episode_id)
    if not langs:
        return None
    for lang in langs:
        code = lang.get('code', '')
        name = lang.get('name', '').lower()
        if preferred == 'sub' and (code == 'sub' or 'sub' in name or 'japanese' in name):
            return lang.get('embed_url')
        if preferred == 'dub' and (code == 'dub' or 'dub' in name or 'english' in name):
            return lang.get('embed_url')
    return langs[0].get('embed_url') if langs else None

def extract_stream(embed_url):
    if embed_url in cache_stream:
        return cache_stream[embed_url]
    try:
        cmd = [
            "yt-dlp", "-g", "--no-playlist", "--no-check-certificate",
            "--extractor-args", "generic:impersonate", embed_url
        ]
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=30)
        if result.returncode == 0 and result.stdout.strip():
            stream_url = result.stdout.strip().split('\n')[0]
            cache_stream[embed_url] = stream_url
            return stream_url
    except Exception as e:
        print(f"yt-dlp error: {e}")
    cache_stream[embed_url] = None
    return None

def get_anime_info(anime_id):
    if anime_id in cache_info:
        return cache_info[anime_id]
    
    print(f"[Scrape] Info for anime {anime_id}")
    url = f"{BASE_URL}/anime/a-{anime_id}" 
    try:
        res = session.get(url)
        res.raise_for_status()
        soup = BeautifulSoup(res.text, "html.parser")

        h1 = soup.find("h1")
        title = h1.text.strip() if h1 else f"Anime {anime_id}"

        og_img = soup.find("meta", property="og:image")
        poster_url = og_img["content"] if og_img else None

        synopsis = ""
        synopsis_header = soup.find(lambda tag: tag.name == "h2" and "Synopsis" in tag.text)
        if synopsis_header:
            p_tag = synopsis_header.find_next("p")
            if p_tag:
                synopsis = p_tag.get_text(separator="\n").strip()
        else:
            og_desc = soup.find("meta", property="og:description")
            if og_desc:
                synopsis = og_desc["content"]

        details = {}
        for dt in soup.find_all("dt"):
            key = dt.text.strip().lower()
            dd = dt.find_next_sibling("dd")
            if dd:
                details[key] = dd.text.strip()

        genres = []
        for a in soup.find_all("a", class_="filter-chip text-xs"):
            genres.append(a.text.strip())

        data = {
            "id": anime_id,
            "judul": title,
            "poster_url": poster_url,
            "sinopsis": synopsis,
            "status": details.get("status", "Unknown"),
            "score": details.get("score", "?"),
            "type": details.get("type", "TV"),
            "genres": genres
        }
        
        cache_info[anime_id] = data
        return data
    except Exception as e:
        print(f"Error info: {e}")
        return None

@app.route('/')
def index():
    return "Flask API for AniDB is running."

@app.route('/api/search')
def api_search():
    # 🔥 FIX: Paksa 'q' menjadi lowercase agar tidak case-sensitive
    params = {k: v for k, v in {
        'q': request.args.get('q', '').strip().lower(),
        'type': request.args.get('type', '').strip(),
        'genres': request.args.get('genre', '').strip(),
        'page': request.args.get('page', '').strip()
    }.items() if v}
    
    if not params:
        return jsonify([])
        
    results = browse_anime(params)
    return jsonify(results)

@app.route('/api/anime/<anime_id>/episodes')
def api_episodes(anime_id):
    episodes = get_episodes(anime_id)
    ep_list = []
    for ep in episodes:
        ep_list.append({
            "id": ep['id'],
            "number": ep['number'],
            "title": ep.get('title', ''),
            "anime_title": ep.get('anime_title', ''),
            "poster_url": ep.get('poster_url', None),
            "sinopsis": ep.get('sinopsis', None),
        })
    return jsonify(ep_list)

@app.route('/api/anime/<anime_id>/info')
def api_anime_info(anime_id):
    data = get_anime_info(anime_id)
    if not data:
        return jsonify({"error": "Not found"}), 404
    return jsonify(data)

@app.route('/api/episode/<ep_id>/embed')
def api_embed(ep_id):
    preferred = request.args.get('lang', 'sub')
    embed = get_embed_url(ep_id, preferred)
    return jsonify({"embed_url": embed})

@app.route('/api/episode/<ep_id>/languages')
def api_languages(ep_id):
    langs = get_languages(ep_id)
    return jsonify({"languages": langs})

@app.route('/api/episode/<ep_id>/stream')
def api_stream(ep_id):
    preferred = request.args.get('lang', 'sub')
    embed = get_embed_url(ep_id, preferred)
    if not embed:
        return jsonify({"error": "No embed"}), 404
    stream = extract_stream(embed)
    return jsonify({"stream_url": stream})

if __name__ == '__main__':
    app.run(debug=True, port=5000, host='0.0.0.0')