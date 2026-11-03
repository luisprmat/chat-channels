import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

/**
 * Pages kept in the back/forward cache keep their WebSocket open, which leaves
 * the user in presence channels after navigating away or logging out. Close the
 * connection when the page is hidden and reconnect (pusher-js re-subscribes
 * every channel) if the page is restored from that cache.
 */
window.addEventListener('pagehide', () => window.Echo.connector.pusher.disconnect());

window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        window.Echo.connector.pusher.connect();
    }
});
