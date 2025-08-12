/**
 * Force refresh of profile images
 */
document.addEventListener("DOMContentLoaded", function () {
    // Force reload images with profile_photo_url in the src attribute
    const profileImages = document.querySelectorAll(
        'img[src*="profile_photo_url"]'
    );
    profileImages.forEach((img) => {
        // Add a random parameter to force cache refresh
        const currentSrc = img.getAttribute("src");
        if (currentSrc && !currentSrc.includes("?v=")) {
            img.setAttribute("src", currentSrc + "?v=" + Math.random());
        }
    });

    // Also find any img elements within profile-related elements
    const profileElements = document.querySelectorAll(
        ".ms-3.relative button img, .profile-photo img"
    );
    profileElements.forEach((img) => {
        // Add a random parameter to force cache refresh
        const currentSrc = img.getAttribute("src");
        if (currentSrc) {
            if (currentSrc.includes("?")) {
                // If it already has query parameters, replace the v parameter
                const newSrc = currentSrc.replace(/(\?|&)v=[^&]*/, "");
                img.setAttribute(
                    "src",
                    newSrc +
                        (newSrc.includes("?") ? "&" : "?") +
                        "v=" +
                        Date.now()
                );
            } else {
                // Otherwise, add a new v parameter
                img.setAttribute("src", currentSrc + "?v=" + Date.now());
            }
        }
    });
});
