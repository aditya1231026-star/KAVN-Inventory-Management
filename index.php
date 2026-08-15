<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$currentUser = htmlspecialchars($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KAVN | Minimal Inventory</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #fafafa;
            --bg-surface: #ffffff;
            --sidebar-bg: #ffffff;
            --text-main: #18181b;
            --text-muted: #71717a;
            --border: #e4e4e7;
            --border-hover: #d4d4d8;
            --accent: #18181b;
            --accent-hover: #27272a;
            --accent-light: #f4f4f5;
            
            /* Status Colors Muted */
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --success: #10b981;
            --success-bg: #ecfdf5;
            
            --transition: all 0.2s ease;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d4d4d8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #a1a1aa; }

        body { display: flex; background: var(--bg-main); color: var(--text-main); height: 100vh; overflow: hidden; letter-spacing: -0.2px; }

        /* Minimal Sidebar */
        .sidebar { width: 260px; background: var(--sidebar-bg); border-right: 1px solid var(--border); display: flex; flex-direction: column; }
        .sidebar-header { padding: 30px 24px; font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); }
        .sidebar-header i { font-size: 24px; }
        .sidebar ul { list-style: none; flex-grow: 1; padding: 24px 12px; }
        .sidebar li { padding: 10px 16px; margin-bottom: 4px; border-radius: 8px; cursor: pointer; color: var(--text-muted); font-weight: 500; display: flex; align-items: center; gap: 12px; font-size: 14px; transition: var(--transition); }
        .sidebar li i { font-size: 18px; }
        .sidebar li:hover { background: var(--accent-light); color: var(--text-main); }
        .sidebar li.active { background: var(--accent-light); color: var(--text-main); font-weight: 600; }

        .main { flex-grow: 1; padding: 40px 48px; overflow-y: auto; height: 100vh; }
        .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header-flex h1 { font-size: 24px; font-weight: 700; }
        
        .user-profile { display: flex; align-items: center; gap: 12px; background: var(--bg-surface); padding: 6px 12px 6px 16px; border-radius: 40px; border: 1px solid var(--border); }
        .user-profile img { width: 28px; height: 28px; border-radius: 50%; }
        
        /* Minimal Cards */
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 40px; }
        .card { background: var(--bg-surface); padding: 24px; border-radius: 12px; border: 1px solid var(--border); display: flex; align-items: center; gap: 16px; transition: var(--transition); }
        .card:hover { border-color: var(--border-hover); }
        .card-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 20px; background: var(--accent-light); color: var(--text-main); }
        .card-info p { color: var(--text-muted); font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-info h3 { font-size: 24px; font-weight: 700; margin-top: 4px; }

        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        .page { background: var(--bg-surface); padding: 32px; border-radius: 12px; border: 1px solid var(--border); margin-bottom: 24px; }
        .page h3 { margin-bottom: 24px; font-size: 16px; font-weight: 600; display: flex; align-items: center; gap: 8px; }

        /* Minimal Tables */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; white-space: nowrap; }
        th { text-align: left; padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 12px; font-weight: 600; text-transform: uppercase; }
        td { padding: 16px; border-bottom: 1px solid var(--border); font-size: 14px; font-weight: 400; color: var(--text-main); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--bg-main); }

        .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; background: var(--accent-light); border: 1px solid var(--border); }
        .badge-low { background: var(--danger-bg); color: var(--danger); border-color: #fca5a5; }

        /* Minimal Forms */
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 32px; }
        .form-grid input { padding: 10px 16px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; outline: none; background: var(--bg-surface); transition: var(--transition); }
        .form-grid input:focus { border-color: var(--accent); }
        .form-grid button { background: var(--accent); color: white; border: none; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 8px; }
        .form-grid button:hover { background: var(--accent-hover); }
        .btn-outline { background: transparent !important; border: 1px solid var(--border) !important; color: var(--text-main) !important; }
        .btn-outline:hover { border-color: var(--text-main) !important; background: var(--bg-main) !important; }

        .hidden { display: none !important; }
        .chart-container { height: 320px; position: relative; width: 100%; }
        
        .action-btns { display: flex; gap: 8px; }
        .btn-icon { width: 32px; height: 32px; border-radius: 6px; border: 1px solid var(--border); background: var(--bg-surface); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: var(--transition); font-size: 16px; color: var(--text-muted); }
        .btn-icon:hover { color: var(--danger); border-color: var(--danger); }

        .input-group { position: relative; width: 280px; margin-bottom: 24px; }
        .input-group i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 18px; }
        .input-group input { width: 100%; padding: 10px 16px 10px 40px; border: 1px solid var(--border); border-radius: 8px; outline: none; font-size: 14px; background: var(--bg-surface); }
        .input-group input:focus { border-color: var(--accent); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header"><i class="ph ph-hexagon"></i> KAVN</div>
    <ul>
        <li class="active" onclick="showPage('dashboard', this)"><i class="ph ph-squares-four"></i> Overview</li>
        <li onclick="showPage('products', this)"><i class="ph ph-package"></i> Inventory</li>
        <li onclick="showPage('receipts', this)"><i class="ph ph-arrow-down-left"></i> Receive</li>
        <li onclick="showPage('transfer', this)"><i class="ph ph-arrows-left-right"></i> Transfer</li>
        <li onclick="showPage('delivery', this)"><i class="ph ph-arrow-up-right"></i> Dispatch</li>
        <li onclick="showPage('adjustment', this)"><i class="ph ph-sliders-horizontal"></i> Adjust</li>
        <li onclick="showPage('ledger', this)"><i class="ph ph-list-dashes"></i> Audit Log</li>
    </ul>
    <div style="padding: 24px 12px;">
        <button onclick="window.location.href='logout.php'" style="background: transparent; border: 1px solid var(--border); width: 100%; padding: 10px; border-radius: 8px; color: var(--text-main); display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; font-weight: 500; font-size: 14px; transition: 0.2s;">
            <i class="ph ph-sign-out"></i> Sign out
        </button>
    </div>
</div>

<div class="main">
    <div class="header-flex">
        <h1 id="title">Overview</h1>
        <div class="user-profile">
            <div style="text-align: right; line-height: 1.2;">
                <span style="font-size: 13px; font-weight: 600;"><?php echo $currentUser; ?></span>
            </div>
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($currentUser); ?>&background=18181b&color=ffffff" alt="Avatar">
        </div>
    </div>

    <div id="dashboardPage">
        <div class="cards">
            <div class="card">
                <div class="card-icon"><i class="ph ph-package"></i></div>
                <div class="card-info"><p>Total Items</p><h3 id="totalProducts">0</h3></div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="ph ph-warning-circle"></i></div>
                <div class="card-info"><p>Critical Stock</p><h3 id="lowStock">0</h3></div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="ph ph-trend-down"></i></div>
                <div class="card-info"><p>Received Events</p><h3 id="receiptCount">0</h3></div>
            </div>
            <div class="card">
                <div class="card-icon"><i class="ph ph-trend-up"></i></div>
                <div class="card-info"><p>Dispatch Events</p><h3 id="deliveryCount">0</h3></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="page">
                <h3>Stock Levels</h3>
                <div class="chart-container"><canvas id="stockChart"></canvas></div>
            </div>
            <div class="page">
                <h3>Warehouse Locations</h3>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Location</th><th>Items</th><th>Total Qty</th></tr></thead>
                        <tbody id="warehouseTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="productsPage" class="page hidden">
        <div class="input-group">
            <i class="ph ph-magnifying-glass"></i>
            <input id="searchBox" placeholder="Search product or SKU..." onkeyup="searchSKU()">
        </div>
        
        <div class="form-grid">
            <input id="pName" placeholder="Product Name">
            <input id="pSKU" placeholder="SKU Code">
            <input id="pCategory" placeholder="Category">
            <input id="pStock" type="number" placeholder="Stock">
            <input id="pWarehouse" placeholder="Warehouse">
            <button onclick="addProduct()">Add Product</button>
        </div>
        
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Product Info</th><th>SKU</th><th>Category</th><th>Warehouse</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody id="productTable"></tbody>
            </table>
        </div>
    </div>

    <div id="receiptsPage" class="page hidden">
        <div class="form-grid" style="max-width: 600px;">
            <input id="rProduct" placeholder="Product Name">
            <input id="rQty" type="number" placeholder="Quantity">
            <button onclick="receive()">Confirm Receipt</button>
        </div>
        <div class="table-wrapper">
            <table id="receiptsTableContainer">
                <thead><tr><th>Product</th><th>Qty Received</th><th>Notes</th><th>Date</th></tr></thead>
                <tbody id="receiptsTable"></tbody>
            </table>
        </div>
    </div>
    
    <div id="transferPage" class="page hidden">
        <div class="form-grid" style="max-width: 800px;">
            <input id="tProduct" placeholder="Product Name">
            <input id="tFrom" placeholder="From Location">
            <input id="tTo" placeholder="To Location">
            <button onclick="transfer()" class="btn-outline">Move Stock</button>
        </div>
    </div>

    <div id="deliveryPage" class="page hidden">
        <div class="form-grid" style="max-width: 600px;">
            <input id="dProduct" placeholder="Product Name">
            <input id="dQty" type="number" placeholder="Quantity">
            <button onclick="deliver()" class="btn-outline">Process Dispatch</button>
        </div>
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Product</th><th>Qty Dispatched</th><th>Notes</th><th>Date</th></tr></thead>
                <tbody id="deliveryTable"></tbody>
            </table>
        </div>
    </div>

    <div id="adjustmentPage" class="page hidden">
        <div class="form-grid" style="max-width: 800px;">
            <input id="aProduct" placeholder="Product Name">
            <input id="aQty" type="number" placeholder="Qty (+ or -)">
            <input id="aReason" placeholder="Reason">
            <button onclick="adjust()">Log Adjustment</button>
        </div>
    </div>

    <div id="ledgerPage" class="page hidden">
        <div class="table-wrapper">
            <table>
                <thead><tr><th>Event Type</th><th>Product</th><th>Quantity</th><th>Details</th><th>Date</th></tr></thead>
                <tbody id="ledgerTable"></tbody>
            </table>
        </div>
    </div>

</div>

<script>
    let inventory = [];
    let ledger = [];
    let stockChart;

    async function loadData() {
        try {
            const response = await fetch('api.php?action=get_data');
            const data = await response.json();
            inventory = data.inventory;
            ledger = data.ledger;
            updateDashboard();
        } catch (error) { console.error("Error:", error); }
    }

    function showPage(pageId, element) {
        document.querySelectorAll('.main > .page, .main > #dashboardPage').forEach(p => p.classList.add('hidden'));
        document.getElementById(pageId + "Page").classList.remove('hidden');
        document.getElementById("title").innerText = element.innerText;
        document.querySelectorAll('.sidebar li').forEach(li => li.classList.remove('active'));
        element.classList.add('active');
        updateDashboard();
    }

    async function addProduct() {
        const p = {
            action: 'add_product', name: document.getElementById("pName").value,
            sku: document.getElementById("pSKU").value, category: document.getElementById("pCategory").value,
            warehouse: document.getElementById("pWarehouse").value, stock: parseInt(document.getElementById("pStock").value) || 0
        };
        if(!p.name || !p.sku) return alert("Fill Name and SKU");
        await fetch('api.php', { method: 'POST', body: JSON.stringify(p) });
        ["pName", "pSKU", "pCategory", "pStock", "pWarehouse"].forEach(id => document.getElementById(id).value = "");
        loadData();
    }

    async function deleteProduct(sku) {
        if(confirm("Delete item?")) {
            await fetch('api.php', { method: 'POST', body: JSON.stringify({ action: 'delete_product', sku: sku }) });
            loadData();
        }
    }

    async function processTransaction(payload) {
        const res = await fetch('api.php', { method: 'POST', body: JSON.stringify(payload) });
        const data = await res.json();
        if(data.success) { loadData(); } else { alert(data.error); }
    }

    function receive() {
        const name = document.getElementById("rProduct").value;
        const qty = parseInt(document.getElementById("rQty").value);
        if(!name || qty <= 0 || isNaN(qty)) return;
        processTransaction({ action: 'transaction', type: 'Stock In', name: name, qty: qty, logQty: `+${qty}`, detail: 'Supplier Delivery' });
        document.getElementById("rProduct").value = ""; document.getElementById("rQty").value = "";
    }

    function transfer() {
        const name = document.getElementById("tProduct").value;
        const fromLoc = document.getElementById("tFrom").value;
        const toLoc = document.getElementById("tTo").value;
        if(!name || !fromLoc || !toLoc) return;
        processTransaction({ action: 'transaction', type: 'Transfer', name: name, qty: 0, logQty: "0", detail: `${fromLoc} → ${toLoc}`, warehouse: toLoc });
        document.getElementById("tProduct").value = ""; document.getElementById("tFrom").value = ""; document.getElementById("tTo").value = "";
    }

    function deliver() {
        const name = document.getElementById("dProduct").value;
        const qty = parseInt(document.getElementById("dQty").value);
        const item = inventory.find(p => p.name.toLowerCase() === name.toLowerCase());
        if(!item || item.stock < qty || qty <= 0 || isNaN(qty)) return alert("Invalid stock");
        processTransaction({ action: 'transaction', type: 'Stock Out', name: name, qty: -qty, logQty: `-${qty}`, detail: 'Customer Dispatch' });
        document.getElementById("dProduct").value = ""; document.getElementById("dQty").value = "";
    }

    function adjust() {
        const name = document.getElementById("aProduct").value;
        const qty = parseInt(document.getElementById("aQty").value);
        const reason = document.getElementById("aReason").value || "Manual Adjustment";
        if(!name || isNaN(qty)) return;
        const sign = qty > 0 ? "+" : "";
        processTransaction({ action: 'transaction', type: 'Adjustment', name: name, qty: qty, logQty: `${sign}${qty}`, detail: reason });
        document.getElementById("aProduct").value = ""; document.getElementById("aQty").value = ""; document.getElementById("aReason").value = "";
    }

    function updateDashboard() {
        document.getElementById("totalProducts").innerText = inventory.length;
        document.getElementById("lowStock").innerText = inventory.filter(p => p.stock < 5).length;
        document.getElementById("receiptCount").innerText = ledger.filter(l => l.type === "Stock In").length;
        document.getElementById("deliveryCount").innerText = ledger.filter(l => l.type === "Stock Out").length;
        renderProducts(); renderWarehouseTable(); renderLedgers(); renderChart();
    }

    function renderProducts() {
        const tbody = document.getElementById("productTable"); tbody.innerHTML = "";
        inventory.forEach((p) => {
            const isLow = p.stock < 5;
            const stockClass = isLow ? 'badge-low' : '';
            const statusText = isLow ? 'Low Stock' : p.stock + ' Units';
            tbody.innerHTML += `<tr>
                <td><strong>${p.name}</strong></td>
                <td><span style="color: #71717a; font-family: monospace;">${p.sku}</span></td>
                <td>${p.category}</td><td>${p.warehouse}</td>
                <td><span class="badge ${stockClass}">${statusText}</span></td>
                <td><div class="action-btns"><button class="btn-icon" onclick="deleteProduct('${p.sku}')"><i class="ph ph-trash"></i></button></div></td>
            </tr>`;
        });
    }

    function renderLedgers() {
        const lTable = document.getElementById("ledgerTable");
        const rTable = document.getElementById("receiptsTable");
        const dTable = document.getElementById("deliveryTable");
        lTable.innerHTML = ""; if(rTable) rTable.innerHTML = ""; if(dTable) dTable.innerHTML = "";

        ledger.forEach(l => {
            const row = `<tr><td>${l.type}</td><td><strong>${l.product}</strong></td><td>${l.qty}</td><td style="color:#71717a">${l.detail}</td><td style="color:#a1a1aa; font-size:12px;">${l.date}</td></tr>`;
            lTable.innerHTML += row;
            if(l.type === "Stock In" && rTable) rTable.innerHTML += row;
            if(l.type === "Stock Out" && dTable) dTable.innerHTML += row;
        });
    }

    function renderWarehouseTable() {
        const map = {};
        inventory.forEach(p => {
            if(!map[p.warehouse]) map[p.warehouse] = {count: 0, stock: 0};
            map[p.warehouse].count++; map[p.warehouse].stock += p.stock;
        });
        const tbody = document.getElementById("warehouseTable"); tbody.innerHTML = "";
        for(let w in map) {
            tbody.innerHTML += `<tr><td><strong>${w || "Unassigned"}</strong></td><td>${map[w].count}</td><td>${map[w].stock}</td></tr>`;
        }
    }

    function renderChart() {
        const ctx = document.getElementById("stockChart").getContext('2d');
        if(stockChart) stockChart.destroy();
        Chart.defaults.font.family = "'Inter', sans-serif";
        
        // Minimal monochrome chart: Black bars for healthy stock, Light Gray for low stock
        stockChart = new Chart(ctx, {
            type: 'bar',
            data: { labels: inventory.map(p => p.name), datasets: [{ label: 'Stock', data: inventory.map(p => p.stock), backgroundColor: inventory.map(p => p.stock < 5 ? '#e4e4e7' : '#18181b'), borderRadius: 4 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f4f4f5' }, border: { display: false } }, x: { grid: { display: false }, border: { display: false } } } }
        });
    }

    function searchSKU() {
        const val = document.getElementById("searchBox").value.toLowerCase();
        document.querySelectorAll("#productTable tr").forEach(row => { row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none"; });
    }

    loadData();
</script>
</body>
</html>