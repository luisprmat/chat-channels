import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "mercure",
    host: import.meta.env.VITE_MERCURE_HUB_URL,
});

/**
 * Pages kept in the back/forward cache keep their Mercure streams open, which
 * leaves the user in presence channels after navigating away or logging out.
 * Close the connection when the page is hidden. Disconnecting forgets the joined
 * channels, so a page that had any is reloaded if restored from that cache, which
 * joins them again and also picks up the current session and messages.
 */
let hadChannelsWhenHidden = false;

window.addEventListener("pagehide", () => {
    hadChannelsWhenHidden =
        Object.keys(window.Echo.connector.channels).length > 0;

    window.Echo.disconnect();
});

window.addEventListener("pageshow", (event) => {
    if (event.persisted && hadChannelsWhenHidden) {
        window.location.reload();
    }
});
