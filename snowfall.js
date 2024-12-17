const snowfallContainer = document.querySelector('.snowfall');

function createSnowflake() {
    const snowflake = document.createElement('div');
    snowflake.classList.add('snowflake');

    // Randomize the size of the snowflake
    const size = Math.random() * 10 + 5; // Size between 5px and 15px
    snowflake.style.width = `${size}px`;
    snowflake.style.height = `${size}px`;

    // Randomize the position
    snowflake.style.left = `${Math.random() * 100}vw`; // Position across the viewport width

    // Set animation duration based on size
    const duration = Math.random() * 3 + 2; // Duration between 2s and 5s
    snowflake.style.animationDuration = `${duration}s`;

    // Append the snowflake to the container
    snowfallContainer.appendChild(snowflake);

    // Remove the snowflake after it falls
    snowflake.addEventListener('animationend', () => {
        snowflake.remove();
    });
}

// Create snowflakes at intervals
setInterval(createSnowflake, 300); // Create a new snowflake every 300ms