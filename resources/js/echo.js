import Echo from "laravel-echo";

window.Echo = new Echo({
    broadcaster: "mercure",
    host: import.meta.env.VITE_MERCURE_HUB_URL,
});

/**
 * Pages kept in the back/forward cache keep their connection open, which leaves
 * the user in presence channels after navigating away or logging out. Close the
 * connection when the page is hidden and reload if the page is restored from
 * that cache, since disconnecting makes the Mercure connector forget its channels.
 */
window.addEventListener("pagehide", () => window.Echo.disconnect());

window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
        window.location.reload();
    }
});
