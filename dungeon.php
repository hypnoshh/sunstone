<?php
// Fetch the API data
$data = file_get_contents('https://holychikenz.github.io/MWIApi/milkyapi.json');
$json = json_decode($data, true);

$market = $json['market'];
$time = $json['time'];

// Define dungeon items and their token costs
$dungeons = [
    'D1' => [
        'Chimerical Essence' => 1,
        'Griffin Leather' => 600,
        'Manticore Sting' => 1000,
        'Jackalope Antler' => 1200,
        'Dodocamel Plume' => 3000,
        'Griffin Talon' => 3000
    ],
    'D2' => [
        'Sinister Essence' => 1,
        "Acrobat's Ribbon" => 2000,
        "Magician's Cloth" => 2000,
        'Chaotic Chain' => 3000,
        'Cursed Ball' => 3000
    ],
    'D3' => [
        'Enchanted Essence' => 1,
        'Royal Cloth' => 2000,
        "Knight's Ingot" => 2000,
        "Bishop's Scroll" => 2000,
        'Regal Jewel' => 3000,
        'Sundering Jewel' => 3000
    ],
    'D4' => [
        'Pirate Essence' => 1,
        'Marksman Brooch' => 2000,
        'Corsair Crest' => 2000,
        'Damaged Anchor' => 2000,
        'Maelstrom Plating' => 2000,
        'Kraken Leather' => 2000,
        'Kraken Fang' => 3000
    ]
];

function formatItem($name, $tokens, $market) {
    $ask = isset($market[$name]['ask']) ? number_format($market[$name]['ask']) : 'N/A';
    $bid = isset($market[$name]['bid']) ? number_format($market[$name]['bid']) : 'N/A';

    return "<tr><td>$name</td><td>$tokens</td><td>$ask</td><td>$bid</td></tr>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dungeon Item Prices</title>
<style>
  body {
    background: var(--bg-color);
    color: var(--text-color);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 20px;
  }
  :root {
    --bg-color: #0d1117;
    --text-color: #c9d1d9;
    --heading-color: #58a6ff;
    --table-bg: #161b22;
    --table-header-bg: #21262d;
    --table-hover-bg: #21262d;
  }
  .light-mode {
    --bg-color: #ffffff;
    --text-color: #000000;
    --heading-color: #007acc;
    --table-bg: #f3f3f3;
    --table-header-bg: #d1d5da;
    --table-hover-bg: #e1e4e8;
  }
  h1, h2 {
    color: var(--heading-color);
  }
  table {
    border-collapse: collapse;
    width: 100%;
    margin-bottom: 30px;
    background: var(--table-bg);
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    border-radius: 8px;
    overflow: hidden;
  }
  th, td {
    padding: 12px 15px;
    text-align: left;
  }
  th {
    background: var(--table-header-bg);
    color: var(--text-color);
    font-weight: bold;
    border-bottom: 1px solid #30363d;
  }
  tr:nth-child(even) {
    background: var(--table-bg);
  }
  tr:nth-child(odd) {
    background: var(--bg-color);
  }
  tr:hover {
    background: var(--table-hover-bg);
  }
  .refresh-btn, .theme-btn {
    display: inline-block;
    padding: 10px 20px;
    background: #238636;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
    margin-right: 10px;
  }
  .refresh-btn:hover, .theme-btn:hover {
    background: #2ea043;
  }
  .footer {
    margin-top: 20px;
    color: #8b949e;
    font-size: 14px;
  }
</style>
<script>
  function setCookie(name, value, days) {
    const d = new Date();
    d.setTime(d.getTime() + (days*24*60*60*1000));
    document.cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
  }

  function getCookie(name) {
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name + "=") == 0) {
        return c.substring(name.length + 1, c.length);
      }
    }
    return "";
  }

  function toggleTheme() {
    document.body.classList.toggle('light-mode');
    setCookie('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark', 365);
  }

  window.onload = function() {
    if (getCookie('theme') === 'light') {
      document.body.classList.add('light-mode');
    }
  }
</script>
</head>
<body>

<h1>Dungeon Item Prices</h1>

<?php foreach ($dungeons as $dungeon => $items): ?>
    <h2><?php echo htmlspecialchars($dungeon); ?></h2>
    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Tokens</th>
          <th>Ask Price</th>
          <th>Bid Price</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item => $tokens): ?>
          <?php echo formatItem($item, $tokens, $market); ?>
        <?php endforeach; ?>
      </tbody>
    </table>
<?php endforeach; ?>

<div class="footer">
  Last Updated: <?php echo date('Y-m-d H:i:s', (int)$time); ?>
</div>

<form method="post">
    <button class="refresh-btn" onclick="window.location.reload(); return false;">Refresh</button>
    <button class="theme-btn" type="button" onclick="toggleTheme()">Toggle Dark/Light Mode</button>
</form>

</body>
</html>
