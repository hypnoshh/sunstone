// dungeons_full.js

// Example API endpoint for price data (replace this with your real API endpoint)
const priceApiUrl = 'https://api.idle-mmo.com/item-prices';

// Dungeon loot tables (simplified version for this example)
const lootTables = {
  'Enchanted Chest': [...],
  'Sinister Chest': [...],
  'Chimerical Chest': [...],
  'Pirate Chest': [...]
};

const specialChests = {
  'Large Treasure Chest': [...]
};

async function fetchPrices() {
  const response = await fetch(priceApiUrl);
  const data = await response.json();
  return data; // { itemName: price, itemName2: price, ... }
}

function calculateItemValue(item, prices) {
  const avgQty = (item.minQuantity + item.maxQuantity) / 2;
  const price = prices[item.name] || 0;
  return (item.probability / 100) * avgQty * price;
}

function generateDungeonTable(dungeonName, table, prices) {
  let html = '<table>';
  html += '<thead><tr><th>Item</th><th>Chance (%)</th><th>Quantity</th><th>Value</th></tr></thead><tbody>';
  let bestValue = 0;
  let bestItem = '';

  table.forEach(item => {
    const avgQty = (item.minQuantity + item.maxQuantity) / 2;
    const value = calculateItemValue(item, prices);

    if (value > bestValue) {
      bestValue = value;
      bestItem = item.name;
    }
  });

  table.forEach(item => {
    const avgQty = (item.minQuantity + item.maxQuantity) / 2;
    const value = calculateItemValue(item, prices);

    html += `<tr${item.name === bestItem ? ' class="highlight"' : ''}><td>${item.name}</td><td>${item.probability}%</td><td>${item.minQuantity} - ${item.maxQuantity}</td><td>${value.toFixed(2)}</td></tr>`;
  });

  html += '</tbody></table>';
  return html;
}

async function renderDungeonTables() {
  const prices = await fetchPrices();
  const dungeonDiv = document.getElementById('dungeon-tables');

  for (const [name, table] of Object.entries(lootTables)) {
    const id = name.replace(/\s+/g, '-');
    dungeonDiv.innerHTML += `
      <button id="btn-${id}" class="collapsible" onclick="toggleCollapse('${id}')">${name}</button>
      <div id="${id}" class="content">
        ${generateDungeonTable(name, table, prices)}
      </div>
    `;
    if (getCookie(id) !== 'collapsed') {
      document.getElementById(id).style.display = 'block';
      document.getElementById('btn-' + id).classList.add('active');
    }
  }

  const now = new Date();
  document.getElementById('last-updated').innerText = now.toLocaleString();
}

async function renderAverageTable() {
  const prices = await fetchPrices();
  const avgDiv = document.getElementById('average-values');

  let html = '<h2>Average Chest Values</h2><table><thead><tr><th>Chest</th><th>Average Value</th></tr></thead><tbody>';

  for (const [name, table] of Object.entries(lootTables)) {
    let totalValue = 0;
    table.forEach(item => {
      totalValue += calculateItemValue(item, prices);
    });
    html += `<tr><td>${name}</td><td>${totalValue.toFixed(2)}</td></tr>`;
  }

  html += '</tbody></table>';

  avgDiv.innerHTML = html;
}

async function init() {
  await renderAverageTable();
  await renderDungeonTables();
}

init();
