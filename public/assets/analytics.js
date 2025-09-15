(function () {
    // Generate or reuse a session ID for this browser
    const sessionId = localStorage.getItem("analytics_session") || crypto.randomUUID();
    localStorage.setItem("analytics_session", sessionId);

    /**
     * Send analytics event to Laravel
     */
    function sendEvent(eventType, target = null, pageNumber = null, duration = 0, signature = null, location = null) {
        fetch(window.analyticsEndpoint, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.csrfToken
            },
            body: JSON.stringify({
                session_id: sessionId,
                event_type: eventType,
                target: target,        // consistent with controller
                page_number: pageNumber,
                duration: duration,
                signature: signature,  // always include for PDF events
                location: location,
                device: navigator.userAgent,
                platform: navigator.platform,
                browser: navigator.vendor,
            })
        }).catch(err => console.error("Analytics error:", err));
    }

    // Try Geolocation API once per session
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const coords = {
                    latitude: pos.coords.latitude,
                    longitude: pos.coords.longitude,
                };
                sendEvent("geolocation", null, null, 0, null, coords);
            },
            (err) => {
                console.warn("Geolocation denied:", err);
                // fallback → Laravel will resolve IP
                sendEvent("geolocation_denied");
            }
        );
    }

    /**
     * Web Page Tracking
     */
    window.addEventListener("load", () => {
        sendEvent("page_view", null);

        const startTime = Date.now();
        window.addEventListener("beforeunload", () => {
            const duration = Math.round((Date.now() - startTime) / 1000);
            sendEvent("page_duration", null, null, duration);
        });
    });

    /**
     * PDF Tracking
     */
    let currentPage = null;
    let pageStartTime = null;
    let pdfId = null;
    let pdfSignature = null;

    // Call this from Blade: initPdfAnalytics(pdfId, pdfSignature)
    window.initPdfAnalytics = function (id, signature) {
        pdfId = id;
        pdfSignature = signature;
        sendEvent("pdf_open", pdfId, null, 0, pdfSignature);
    }

    // Call this on page change from PDF.js
    window.onPdfPageChange = function (pageNum) {
        const now = Date.now();

        // Send time spent on previous page
        if (currentPage !== null && pageStartTime !== null) {
            const duration = Math.round((now - pageStartTime) / 1000);
            sendEvent("pdf_page_view", pdfId, currentPage, duration, pdfSignature);
        }

        currentPage = pageNum;
        pageStartTime = now;
    }

    // Send final page duration on exit
    window.addEventListener("beforeunload", () => {
        if (currentPage !== null && pageStartTime !== null) {
            const duration = Math.round((Date.now() - pageStartTime) / 1000);
            sendEvent("pdf_page_view", pdfId, currentPage, duration, pdfSignature);
        }
    });
})();
