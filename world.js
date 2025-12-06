document.addEventListener('DOMContentLoaded', function() {
    const countryInput = document.getElementById('country');
    const lookupBtn = document.getElementById('lookup');
    const resultDiv = document.getElementById('result');

    lookupBtn.addEventListener('click', function() {
        const country = countryInput.value.trim();
        if (country) {
            fetchCountryData(country);
        } else {
            resultDiv.innerHTML = '<p>Please enter a country name.</p>';
        }
    });
    
    function fetchCountryData(country) {
        resultDiv.innerHTML = '<p>Loading...</p>';
        
        const xhr = new XMLHttpRequest();
        const url = `world.php?country=${encodeURIComponent(country)}`;
        
        xhr.open('GET', url, true);
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    resultDiv.innerHTML = xhr.responseText;
                } else {
                    resultDiv.innerHTML = `<p>Error loading data. Status: ${xhr.status}</p>`;
                }
            }
        };
        
        xhr.onerror = function() {
            resultDiv.innerHTML = '<p>Network error. Please check if the server is running.</p>';
        };
        
        xhr.send();
    }
    
    countryInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            lookupBtn.click();
        }
    });
});