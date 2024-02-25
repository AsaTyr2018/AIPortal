<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
$config = parse_ini_file("config.ini", true);
echo "<title>" . $config['titel']['titel_name'] . "</title>";
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #121212;
    color: white;
  }
  .menu {
    display: flex;
    justify-content: space-around;
    padding: 10px;
    background-color: #333;
  }
  .menu button {
    background-color: #555;
    color: white;
    border: none;
    padding: 10px 20px;
    margin: 0 5px;
    border-radius: 15px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    position: relative; /* Wichtig für die Positionierung des Dropdowns */
  }
  .menu button:hover {
    background-color: #777;
  }
  /* Dropdown-Container */
  .dropdown {
    position: absolute;
    display: none;
    background-color: #555;
    border-radius: 5px;
    top: 100%;
    left: 0;
    z-index: 1;
  }
  /* Dropdown-Links */
  .dropdown a {
    display: block;
    color: white;
    padding: 10px;
    text-decoration: none;
    transition: background-color 0.3s ease;
  }
  .dropdown a:hover {
    background-color: #777;
  }
  /* Anzeigen des Dropdown-Inhalts beim Hover */
  .menu button:hover .dropdown {
    display: block;
  }
    iframe {
    width: 100%;
    height: 1000px;
    border: none;
    margin-top: 10px;
    border-radius: 15px;
  }
</style>
</head>
<body>

<div class="menu">
  <?php
  // Zähle nur Hauptmenüpunkte
  $menuCount = count(array_filter(array_keys($config['menu']), function($key) {
    return strpos($key, '_dropdown_') === false && strpos($key, '_name') !== false;
  }));

  for ($i = 1; $i <= $menuCount; $i++) {
    if (isset($config['menu']["menu_{$i}_url"])) {
      $url = $config['menu']["menu_{$i}_url"];
      $name = $config['menu']["menu_{$i}_name"];
      // Überprüfen, ob es sich um einen Dropdown-Menübutton handelt
      if ($url === "#") {
        // Für Dropdown-Menübuttons keine onclick-Aktion setzen
        echo "<button>{$name}";
        echo '<div class="dropdown">';
        for ($j = 1; isset($config['menu']["menu_{$i}_dropdown_{$j}_name"]); $j++) {
          $dropdownUrl = $config['menu']["menu_{$i}_dropdown_{$j}_url"];
          $dropdownName = $config['menu']["menu_{$i}_dropdown_{$j}_name"];
          echo "<a href=\"#\" onclick=\"setIframeSource('{$dropdownUrl}', event)\">{$dropdownName}</a>";
        }
        echo '</div>'; // Schließe den Dropdown-Container
      } else {
        // Für normale Menübuttons die onclick-Aktion setzen
        echo "<button onclick=\"setIframeSource('{$url}', event)\">{$name}</button>";
      }
      echo '</button>'; // Schließe den Button
    }
  }
  ?>
</div>


<iframe id="iframe" src="./homepage.html"></iframe>

<script>
// Wir fügen das Event-Objekt als Parameter hinzu
  function setIframeSource(url, event) {
    // Verhindern, dass das Standard-Link-Verhalten ausgelöst wird
    event.preventDefault();
    // Verhindern, dass das Event weiter im DOM aufsteigt
    event.stopPropagation();
    document.getElementById('iframe').src = url;
  }
</script>
<footer style="text-align: center; padding: 20px; background-color: #333; color: white;">
  <p>&copy; 2024 <a href="https://github.com/AsaTyr2018/AIPortal" target="_blank" style="color: white; text-decoration: none;">AsaTyr</a>. Released under the GNU General Public License (GPL).</p>
</footer>
</body>
</html>
