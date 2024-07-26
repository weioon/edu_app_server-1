const express = require('express');
const axios = require('axios');
const cors = require('cors'); // Import the cors package

const app = express();
const port = 3000;

// Use the cors middleware
app.use(cors());

// Your OpenWeather API key
const apiKey = '168013637d1447c882c3ba794a5e1a76';

// Endpoint to fetch weather data
app.get('/weather', async (req, res) => {
    try {
        // Define latitude and longitude (you can also get these from query parameters if you prefer)
        const lat = '5';
        const lon = '100';

        // Fetch weather data from OpenWeather API
        const response = await axios.get(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}`);
        
        // Send the response data
        res.json(response.data);
    } catch (error) {
        console.error(error);
        res.status(500).send('Error fetching weather data');
    }
});

app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});