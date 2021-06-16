module.exports = {
    pwa: {
        name: 'chat_bug_fix',
        short_name: 'chat_bug_fix',
        display: 'standalone',
        start_url: '.',
        themeColor: '#020202',
        msTileColor: '#020202',
        appleMobileWebAppCapable: 'yes',
        appleMobileWebAppStatusBarStyle: 'black',

        workboxOptions: {
            skipWaiting: true
        }
    }
}