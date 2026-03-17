async function weatherCall(city) {
    let response = await fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=ba6ffb002bbe60b898d4f03be29c129a&units=metric`);
    let data = await response.json();

    if (data.cod === "404") {
        return;
    }

    console.log(data); // View the entire response to debug
    console.log(data.name); // City name

    // Get Date and converted Unix timestamp to human-readable format
    let date = new Date(data.dt * 1000);
    let dateString = `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;

    document.getElementById('date').innerText = dateString
    // Populate other weather data
    document.getElementById('temperature').innerText = `${data.main.temp}°C`;
    document.getElementById('city').innerText = data.name;
    document.getElementById('weather').innerText = data.weather[0].description;
    
    // Handle wind speed and direction
    document.getElementById('wind').innerText = `Wind: ${data.wind.speed} m/s`;
    document.getElementById('direction').innerText = `Direction: ${data.wind.deg}°`;

    // Pressure and Humidity
    document.getElementById('pressure').innerText = `Pressure: ${data.main.pressure} hPa`;
    document.getElementById('humidity').innerText = `Humidity: ${data.main.humidity}%`;

    // Handle weather icon and use the appropriate icon URL
    const iconUrl = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
    document.getElementById('image').src = iconUrl;
}

// Initial weather data fetch on page load
weatherCall("Kalaiya");

// Add event listener for search button
document.getElementById('searchbtn').addEventListener('click', function () {
    let name = document.getElementById("searchbar").value;
    console.log(name); // Check what city the user has input
    weatherCall(name);
});





