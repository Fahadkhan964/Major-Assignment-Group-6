document.addEventListener("DOMContentLoaded", function () {
    const timerDisplay = document.getElementById("timer");
    const quizForm = document.getElementById("quizForm");

    if (timerDisplay && typeof TIME_LIMIT_MINUTES !== 'undefined') {
        let timeRemaining = TIME_LIMIT_MINUTES * 60; // Convert to seconds

        const updateTimer = () => {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;

            timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                alert("Time is up! Submitting your answers now.");
                quizForm.submit();
            }

            timeRemaining--;
        };

        // Initial call
        updateTimer();
        
        // Interval
        const timerInterval = setInterval(updateTimer, 1000);
    }
});
