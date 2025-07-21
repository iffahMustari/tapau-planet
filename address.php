<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delivery Location</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f1f5f9;
      margin: 0;
      padding: 20px;
    }

    .modal {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 24px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #374151;
    }

    #map {
      height: 300px;
      border-radius: 8px;
      margin-top: 10px;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      font-size: 1rem;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
    }

    .btn {
      margin-top: 14px;
      padding: 12px 16px;
      font-size: 1rem;
      background: #6366f1;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    .output {
      background: #f3f4f6;
      padding: 12px;
      border-radius: 8px;
      margin-top: 14px;
    }
  </style>
</head>
<body>

  <div class="modal">
    <h2>Confirm Delivery Location</h2>
    <button class="btn" onclick="useMyLocation()">📍 Use My Location</button>
    <div id="map" style="display:none;"></div>
    <input type="text" id="manualAddress" placeholder="Or type your address manually" />
    <div class="output" id="addressOutput">Your address will appear here...</div>
    <button class="btn" onclick="submitAddress()">✅ Confirm & Submit</button>
  </div>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    let map, marker, currentLatLng;
    const apiKey = "0ddc3d7d06ae471fa6c18aa43f83c05c"; // Use OpenCage API

    async function useMyLocation() {
      document.getElementById("map").style.display = "block";

      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async (position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          currentLatLng = { lat, lng };

          setupMap(lat, lng);
          await fetchAddress(lat, lng);
        }, () => {
          alert("Location access denied or unavailable.");
        });
      } else {
        alert("Geolocation is not supported by your browser.");
      }
    }

    function setupMap(lat, lng) {
      if (!map) {
        map = L.map("map").setView([lat, lng], 16);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: "© OpenStreetMap"
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);
        marker.on("dragend", async () => {
          const newPos = marker.getLatLng();
          currentLatLng = newPos;
          await fetchAddress(newPos.lat, newPos.lng);
        });
      } else {
        map.setView([lat, lng], 16);
        marker.setLatLng([lat, lng]);
      }

      document.getElementById("map").style.display = "block";
    }

    async function fetchAddress(lat, lng) {
      const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lng}&key=${apiKey}`;

      try {
        const response = await fetch(url);
        const data = await response.json();
        const address = data.results[0]?.formatted || "Address not found";
        document.getElementById("addressOutput").innerHTML = `
          <strong>Latitude:</strong> ${lat.toFixed(6)}<br>
          <strong>Longitude:</strong> ${lng.toFixed(6)}<br>
          <strong>Address:</strong><br>${address}
        `;
        document.getElementById("manualAddress").value = address;
      } catch {
        document.getElementById("addressOutput").textContent = "❌ Failed to fetch address.";
      }
    }

    async function geocodeAddress(address) {
      const url = `https://api.opencagedata.com/geocode/v1/json?q=${encodeURIComponent(address)}&key=${apiKey}`;
      try {
        const response = await fetch(url);
        const data = await response.json();
        const result = data.results[0];
        if (result) {
          const lat = result.geometry.lat;
          const lng = result.geometry.lng;
          currentLatLng = { lat, lng };
          setupMap(lat, lng);
          await fetchAddress(lat, lng);
        } else {
          alert("❌ Address not found.");
        }
      } catch {
        alert("❌ Failed to geocode address.");
      }
    }

function submitAddress() {
  const manualAddress = document.getElementById("manualAddress").value;

  if (!manualAddress) {
    alert("Please provide your address.");
    return;
  }

  // If lat/lng not set yet (user only filled manually), geocode first
  if (!currentLatLng) {
    geocodeAddress(manualAddress);
    return;
  }

  const lat = currentLatLng.lat;
  const lng = currentLatLng.lng;

  // Send to backend
fetch("save_address.php", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({ address: manualAddress, lat, lng })
})
.then(async res => {
  console.log("Raw response:", res);

  try {
    const data = await res.json();
    console.log("Parsed JSON:", data);

    if (data.success) {
      alert("✅ Address submitted successfully!");
      window.location.href = "checkout.php?showModal=1";
    } else {
      alert("❌ Failed: " + (data.error || "Unknown error."));
    }

  } catch (e) {
    console.error("JSON parsing error:", e);
    alert("❌ Failed to parse server response as JSON.");
  }
})
.catch(err => {
  console.error("FETCH ERROR:", err);
  alert("❌ Failed to submit address.");
});


}

  </script>

</body>
</html>
