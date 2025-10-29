// Time Override Initialization Script
(function () {
    "use strict";

    // Function to initialize time override
    function initTimeOverride() {
        // Fetch time override data from server
        fetch("/time-override/js-data")
            .then((response) => response.json())
            .then((data) => {
                if (data.is_active && data.override_datetime) {
                    // Set override time in JavaScript
                    window.TimeOverrideHelper.setOverrideTime(
                        data.override_datetime
                    );

                    // Update all time displays on the page
                    updateTimeDisplays(data.current_time);

                    // Show time override indicator
                    showTimeOverrideIndicator(data);
                } else {
                    // Clear any existing override
                    window.TimeOverrideHelper.clearOverride();
                    hideTimeOverrideIndicator();
                }
            })
            .catch((error) => {
                console.warn("Failed to load time override data:", error);
            });
    }

    // Function to update time displays on the page
    function updateTimeDisplays(currentTime) {
        // Find all elements with time-related classes or data attributes
        const timeElements = document.querySelectorAll(
            "[data-time], .time-display, .current-time"
        );

        timeElements.forEach((element) => {
            const format = element.dataset.format || "Y-m-d H:i:s";
            element.textContent = window.TimeOverrideHelper.formatTime(format);
        });

        // Update any JavaScript Date objects that might be displayed
        updateJavaScriptDates();
    }

    // Function to update JavaScript Date objects
    function updateJavaScriptDates() {
        // This would update any JavaScript Date objects that are being displayed
        // Implementation depends on how dates are displayed in your app
    }

    // Function to show time override indicator
    function showTimeOverrideIndicator(data) {
        let indicator = document.getElementById("time-override-indicator");

        if (!indicator) {
            indicator = document.createElement("div");
            indicator.id = "time-override-indicator";
            indicator.className = "time-override-indicator";
            indicator.innerHTML = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bx bx-time-five me-2"></i>
                    <strong>Time Override Active:</strong> 
                    Current time: ${data.current_time} | 
                    Real time: ${data.real_time}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            // Insert at the top of the page
            const body = document.body;
            body.insertBefore(indicator, body.firstChild);
        }
    }

    // Function to hide time override indicator
    function hideTimeOverrideIndicator() {
        const indicator = document.getElementById("time-override-indicator");
        if (indicator) {
            indicator.remove();
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", initTimeOverride);
    } else {
        initTimeOverride();
    }

    // Re-initialize when navigating (for SPAs)
    if (window.history && window.history.pushState) {
        const originalPushState = window.history.pushState;
        window.history.pushState = function () {
            originalPushState.apply(window.history, arguments);
            setTimeout(initTimeOverride, 100);
        };

        const originalReplaceState = window.history.replaceState;
        window.history.replaceState = function () {
            originalReplaceState.apply(window.history, arguments);
            setTimeout(initTimeOverride, 100);
        };
    }

    // Listen for time override changes
    window.addEventListener("timeOverrideChanged", function (event) {
        initTimeOverride();
    });
})();

