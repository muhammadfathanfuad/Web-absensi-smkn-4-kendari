// Time Override Helper for JavaScript
class TimeOverrideHelper {
    constructor() {
        this.overrideTime = null;
        this.isActive = false;
        this.init();
    }

    init() {
        // Check if time override is active
        this.checkTimeOverrideStatus();

        // Override Date constructor
        this.overrideDateConstructor();

        // Override Date.now()
        this.overrideDateNow();

        // Override new Date()
        this.overrideNewDate();
    }

    checkTimeOverrideStatus() {
        // This would typically be called from the server
        // For now, we'll check if there's a time override in localStorage
        const overrideData = localStorage.getItem("time_override");
        if (overrideData) {
            const data = JSON.parse(overrideData);
            this.overrideTime = new Date(data.datetime);
            this.isActive = data.is_active;
        }
    }

    overrideDateConstructor() {
        const OriginalDate = window.Date;
        const self = this;

        function Date(...args) {
            if (self.isActive && self.overrideTime) {
                if (args.length === 0) {
                    // new Date() - return override time
                    return new OriginalDate(self.overrideTime);
                } else if (args.length === 1 && typeof args[0] === "string") {
                    // new Date(string) - parse the string but adjust to override time
                    const parsed = new OriginalDate(args[0]);
                    if (!isNaN(parsed.getTime())) {
                        return parsed;
                    }
                }
            }
            return new OriginalDate(...args);
        }

        // Copy static methods
        Object.setPrototypeOf(Date, OriginalDate);
        Object.defineProperty(Date, "prototype", {
            value: OriginalDate.prototype,
            writable: false,
        });

        // Copy static properties
        Object.getOwnPropertyNames(OriginalDate).forEach((name) => {
            if (name !== "prototype" && name !== "length" && name !== "name") {
                Object.defineProperty(
                    Date,
                    name,
                    Object.getOwnPropertyDescriptor(OriginalDate, name)
                );
            }
        });

        window.Date = Date;
    }

    overrideDateNow() {
        const self = this;
        const originalNow = Date.now;

        Date.now = function () {
            if (self.isActive && self.overrideTime) {
                return self.overrideTime.getTime();
            }
            return originalNow.call(Date);
        };
    }

    overrideNewDate() {
        const self = this;
        const originalDate = window.Date;

        // This is handled by the constructor override above
    }

    setOverrideTime(datetime) {
        this.overrideTime = new Date(datetime);
        this.isActive = true;

        // Store in localStorage
        localStorage.setItem(
            "time_override",
            JSON.stringify({
                datetime: datetime,
                is_active: true,
            })
        );
    }

    clearOverride() {
        this.overrideTime = null;
        this.isActive = false;
        localStorage.removeItem("time_override");
    }

    getCurrentTime() {
        if (this.isActive && this.overrideTime) {
            return this.overrideTime;
        }
        return new Date();
    }

    formatTime(format = "Y-m-d H:i:s") {
        const date = this.getCurrentTime();
        // Simple format implementation
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        const hours = String(date.getHours()).padStart(2, "0");
        const minutes = String(date.getMinutes()).padStart(2, "0");
        const seconds = String(date.getSeconds()).padStart(2, "0");

        return format
            .replace("Y", year)
            .replace("m", month)
            .replace("d", day)
            .replace("H", hours)
            .replace("i", minutes)
            .replace("s", seconds);
    }
}

// Initialize the helper
window.TimeOverrideHelper = new TimeOverrideHelper();

// Export for module systems
if (typeof module !== "undefined" && module.exports) {
    module.exports = TimeOverrideHelper;
}

