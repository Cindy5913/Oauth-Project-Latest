<?php
session_start();
if (!isset($_SESSION['user'])) {
    $_SESSION['message'] = "Please login first.";
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Flights Dashboard</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: url('https://plus.unsplash.com/premium_photo-1679830513990-82a4280f41b4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }
    .container {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 20px;
      max-width: 1200px;
      margin: 50px auto;
      color: #1f2937;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    header h1 { margin: 0; }
    a.logout {
      display: inline-block;
      background: #ef4444;
      color: white;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 500;
      transition: background 0.3s;
      cursor: pointer;
    }
    a.logout:hover { background: #dc2626; }

    /* Flight Overview Cards */
    .overview {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 30px;
    }
    .overview-card {
      flex: 1 1 200px;
      background: rgba(255,255,255,0.3);
      padding: 20px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      transition: transform 0.3s;
    }
    .overview-card:hover { transform: translateY(-5px); }
    .overview-card h3 { margin-bottom: 10px; font-size: 16px; }
    .overview-card p { font-size: 18px; font-weight: bold; }

    /* Flight Map */
    #map {
      height: 400px;
      border-radius: 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    /* Flight Tickets */
    .tickets {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
    }
    .ticket {
      background: rgba(255,255,255,0.3);
      border-radius: 15px;
      padding: 20px;
      position: relative;
      overflow: hidden;
      box-shadow: 0 6px 15px rgba(0,0,0,0.2);
      transition: transform 0.3s;
    }
    .ticket:hover { transform: translateY(-5px); }
    .ticket h3 { margin: 0 0 10px 0; font-size: 18px; color: #1f2937; }
    .ticket p { margin: 5px 0; font-size: 14px; color: #374151; }
    .ticket .price { font-weight: bold; font-size: 16px; margin-top: 10px; color: #16a34a; }
    .ticket button {
      margin-top: 10px;
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      background: #ca8a04;
      color: white;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s;
    }
    .ticket button:hover { background: #d97706; }

    /* Logout Confirmation Popup */
    #logoutBox {
      display: none;
      position: fixed;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    .logout-popup {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      padding: 30px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 8px 25px rgba(0,0,0,0.3);
      width: 350px;
      animation: fadeIn 0.3s ease-in-out;
    }
    .logout-popup h2 { margin: 0 0 15px; color: #1f2937; }
    .logout-popup p { color: #374151; margin-bottom: 20px; }
    .logout-popup button {
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s;
      margin: 0 10px;
    }
    .btn-confirm { background: #ef4444; color: white; }
    .btn-confirm:hover { background: #dc2626; }
    .btn-cancel { background: #ca8a04; color: white; }
    .btn-cancel:hover { background: #d97706; }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?> 👋</h1>
      <a href="#" class="logout" onclick="openLogoutPopup()">Logout</a>
    </header>

    <!-- Flight Overview -->
    <h2>Flight Overview</h2>
    <div class="overview">
      <div class="overview-card"><h3>Total Flights</h3><p>3</p></div>
      <div class="overview-card"><h3>Destinations</h3><p>New York, London, Tokyo, Paris, Dubai</p></div>
      <div class="overview-card"><h3>Cheapest Flight</h3><p>$500</p></div>
      <div class="overview-card"><h3>Most Expensive Flight</h3><p>$750</p></div>
    </div>

    <!-- Flight Map -->
    <div id="map"></div>

    <!-- Available Flights -->
    <h2>Available Flights</h2>
    <div class="tickets">
      <div class="ticket">
        <h3>Flight FL123</h3>
        <p>Airline: Airways A</p>
        <p>From: New York → To: London</p>
        <p>Departure: 10:00 AM | Arrival: 10:00 PM</p>
        <p class="price">$500</p>
        <button>Book Now</button>
      </div>
      <div class="ticket">
        <h3>Flight FL456</h3>
        <p>Airline: Airways B</p>
        <p>From: Los Angeles → To: Tokyo</p>
        <p>Departure: 08:00 AM | Arrival: 11:00 PM</p>
        <p class="price">$750</p>
        <button>Book Now</button>
      </div>
      <div class="ticket">
        <h3>Flight FL789</h3>
        <p>Airline: Airways C</p>
        <p>From: Paris → To: Dubai</p>
        <p>Departure: 06:00 AM | Arrival: 02:00 PM</p>
        <p class="price">$600</p>
        <button>Book Now</button>
      </div>
    </div>
  </div>

  <!-- Logout Confirmation Popup -->
  <div id="logoutBox">
    <div class="logout-popup">
      <h2>Confirm Logout</h2>
      <p>Are you sure you want to log out?</p>
      <button class="btn-confirm" onclick="confirmLogout()">Yes, Logout</button>
      <button class="btn-cancel" onclick="closeLogoutPopup()">Cancel</button>
    </div>
  </div>

  <!-- Logout Confirmation Popup -->
  <div id="logoutBox">
    <div class="logout-popup">
      <h2>Confirm Logout</h2>
      <p>Are you sure you want to log out?</p>
      <button class="btn-confirm" onclick="confirmLogout()">Yes, Logout</button>
      <button class="btn-cancel" onclick="closeLogoutPopup()">Cancel</button>
    </div>
  </div>

  <script>
    function openLogoutPopup() {
      document.getElementById("logoutBox").style.display = "flex";
    }
    function closeLogoutPopup() {
      document.getElementById("logoutBox").style.display = "none";
    }
    function confirmLogout() {
      // Go directly to logout.php to destroy session
      window.location.href = "logout.php";
    }

    // Initialize map
    const map = L.map('map').setView([20, 0], 2);//centers the map on latitude 20, longitude 0 (roughly Africa) with zoom level 2 (world view).
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const flights = [
      {from: [40.7128, -74.0060], to: [51.5074, -0.1278]},   // NY → London
      {from: [34.0522, -118.2437], to: [35.6895, 139.6917]}, // LA → Tokyo
      {from: [48.8566, 2.3522], to: [25.276987, 55.296249]}  // Paris → Dubai
    ];
    flights.forEach(flight => {
      L.polyline([flight.from, flight.to], {color: 'orange'}).addTo(map);
      L.marker(flight.from).addTo(map);
      L.marker(flight.to).addTo(map);
    });
  </script>
</body>
</html>
