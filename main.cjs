const { app, BrowserWindow, session } = require("electron");

function createWindow() {
    const win = new BrowserWindow({
        width: 1280,
        height: 720,
        autoHideMenuBar: true, // Menyembunyikan menu bar bawaan desktop
        webPreferences: {
            nodeIntegration: false,
            contextIsolation: true,
            webSecurity: false, // Penting untuk bypass batasan CORS dasar
        },
    });

    // 🔥 JURUS UTAMA: Mencegat dan menghapus header pemblokir Iframe
    session.defaultSession.webRequest.onHeadersReceived((details, callback) => {
        const responseHeaders = Object.assign({}, details.responseHeaders);

        // Hapus semua aturan yang melarang video di-embed
        delete responseHeaders["X-Frame-Options"];
        delete responseHeaders["x-frame-options"];
        delete responseHeaders["content-security-policy"];
        delete responseHeaders["Content-Security-Policy"];

        callback({
            cancel: false,
            responseHeaders: responseHeaders,
        });
    });

    // Pastikan URL selalu memanggil IP lokal abadi agar Electron selalu bisa terbuka
    win.loadURL("http://127.0.0.1:8000");
}

app.whenReady().then(createWindow);

app.on("window-all-closed", () => {
    if (process.platform !== "darwin") app.quit();
});
