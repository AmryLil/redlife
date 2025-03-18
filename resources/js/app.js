import "./bootstrap";

function animateNumber(element, start, end, duration) {
    let startTime = null;

    const updateNumber = (timestamp) => {
        if (!startTime) startTime = timestamp;
        const progress = timestamp - startTime;
        const percentage = Math.min(progress / duration, 1);

        element.textContent = Math.floor(percentage * (end - start) + start);

        if (percentage < 1) {
            requestAnimationFrame(updateNumber);
        }
    };

    requestAnimationFrame(updateNumber);
}
