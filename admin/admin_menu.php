<?php
session_start();
include('../connection.php');

function displayMenuSection($conn, $categoryId, $limit, $sectionTitle)
{
    $stmt = $conn->prepare("SELECT menuId, menuName, menuPrice, menuDesc, menuPic FROM menu WHERE categoryId = ? LIMIT ?");
    $stmt->bind_param("ii", $categoryId, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2 class='section-title'>$sectionTitle</h2>";
    echo '<div class="menu-container">';

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="food-menu-box">';
            echo '  <img src="../images/' . htmlspecialchars($row['menuPic']) . '" class="img-curve" alt="' . htmlspecialchars($row['menuName']) . '" onclick="showMenuDetail(\'' . addslashes($row['menuPic']) . '\',\'' . addslashes($row['menuName']) . '\',' . number_format($row['menuPrice'], 2, '.', '') . ',\'' . addslashes($row['menuDesc']) . '\')">';
            echo '  <div class="food-menu-desc">';
            echo '      <h4>' . htmlspecialchars($row['menuName']) . '</h4>';
            echo '      <p class="food-price">RM ' . number_format($row['menuPrice'], 2) . '</p>';
            echo '      <p class="food-detail">' . htmlspecialchars($row['menuDesc']) . '</p>';
            echo '      <div class="action-buttons">';
            echo '          <a href="admin_updatemenu.php?menuId=' . $row['menuId'] . '" class="btn-update"><i class="fas fa-pen"></i> Update</a>';
            echo '          <a href="admin_deletemenu.php?menuId=' . $row['menuId'] . '" class="btn-delete" onclick="return confirm(\'Are you sure you want to delete this menu?\')"><i class="fas fa-trash"></i> Delete</a>';
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }
    } else {
        echo "<p class='no-data'>Tiada $sectionTitle untuk dipaparkan.</p>";
    }

    echo '</div>';
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menuName   = $_POST['menuName'] ?? '';
    $menuPrice  = $_POST['menuPrice'] ?? 0;
    $menuDesc   = $_POST['menuDesc'] ?? '';
    $categoryId = $_POST['categoryId'] ?? 0;

    $targetDir      = '../images/';
    $fileName       = basename($_FILES['menuPic']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['menuPic']['tmp_name'], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO menu (menuName, menuPrice, menuDesc, menuPic, categoryId) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sdssi", $menuName, $menuPrice, $menuDesc, $fileName, $categoryId);
        $stmt->execute();
        header('Location: admin_menu.php?msg=add_success');
        $stmt->close();
    } else {
        header('Location: admin_menu.php?msg=add_fail');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Menu</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{font-family:system-ui,Arial,sans-serif;background:#f4f6f9;display:flex;min-height:100vh;color:#333}
  /* Sidebar */
  .sidebar{position:fixed;left:0;top:0;width:70px;height:100vh;background:#fff;border-right:1px solid #ddd;transition:width .3s;display:flex;flex-direction:column;align-items:center;padding-top:1rem;z-index:999}
  .sidebar:hover{width:240px;align-items:flex-start;padding-left:1rem}
  .logo-img{width:40px;transition:width .3s}
  .sidebar:hover .logo-img{width:120px}
  .sidebar ul{list-style:none;width:100%}
  .sidebar li{width:100%}
  .sidebar a{display:flex;align-items:center;gap:.75rem;width:100%;padding:.6rem .8rem;border-radius:6px;text-decoration:none;color:#333;white-space:nowrap}
  .sidebar a:hover{background:#f0f0f0;color:#ff914d}
  .sidebar a.active{background:#ff914d;color:#fff}
  .sidebar i{min-width:22px;text-align:center;font-size:18px}
  .sidebar span{opacity:0;width:0;overflow:hidden;transition:opacity .3s,width .3s}
  .sidebar:hover span{opacity:1;width:auto}
  /* Main */
  .main{margin-left:70px;flex:1;padding:2rem;transition:margin-left .3s}
  .sidebar:hover~.main{margin-left:240px}
  h1{text-align:center;margin-bottom:1.5rem}
  .add-btn{display:inline-block;background:#28a745;color:#fff;padding:.6rem 1rem;border-radius:5px;text-decoration:none;margin-bottom:1rem}
  /* Menu grid */
  .menu-container{display:flex;flex-wrap:wrap;gap:1rem;justify-content:center}
  .food-menu-box{width:250px;background:#fff;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,.1);overflow:hidden;transition:transform .2s}
  .food-menu-box:hover{transform:translateY(-4px)}
  .img-curve{width:100%;height:160px;object-fit:cover;cursor:pointer}
  .food-menu-desc{padding:1rem;text-align:center}
  .food-price{color:#ff914d;font-weight:600;margin:.5rem 0}
  .action-buttons a{display:inline-block;padding:.4rem .8rem;border-radius:4px;color:#fff;text-decoration:none;font-size:.85rem;margin:.2rem}
  .btn-update{background:#3498db}
  .btn-delete{background:#dc3545}
  .btn-update:hover{background:#2980b9}
  .btn-delete:hover{background:#c82333}
  .section-title{text-align:center;font-size:1.5rem;margin:2rem 0 1rem}
  .no-data{text-align:center;font-style:italic;margin:1rem auto}
  /* Modal */
  #modalOverlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.6);display:none;justify-content:center;align-items:center;z-index:1000}
  .modal{background:#fff;border-radius:8px;padding:1.5rem;max-width:400px;width:90%;position:relative;text-align:center}
  .modal img{width:100%;max-height:220px;object-fit:cover;border-radius:6px;margin-bottom:1rem}
  .close-modal{position:absolute;top:10px;right:14px;font-size:22px;cursor:pointer;color:#666}
  /* Toast */
  #toast{position:fixed;bottom:20px;right:20px;background:#28a745;color:#fff;padding:.7rem 1.2rem;border-radius:4px;opacity:0;transform:translateY(20px);transition:opacity .4s,transform .4s;z-index:1100}
  #toast.show{opacity:1;transform:translateY(0)}
</style>
</head>
<body>
<nav class="sidebar">
  <img src="../images/CSC264 LOGO 1.png" class="logo-img" alt="Logo" />
  <ul>
    <li><a href="indexadmin.php" class="active"><i class="fas fa-chart-bar"></i><span>Dashboard</span></a></li>
    <li><a href="admin_menu.php"><i class="fas fa-edit"></i><span>Menu</span></a></li>
    <li><a href="admin_sales.php"><i class="fas fa-dollar-sign"></i><span>Sales</span></a></li>
    <li><a href="#"><i class="fas fa-right-from-bracket"></i><span>Logout</span></a></li>
  </ul>
</nav>
<main class="main">
  <h1>Admin Menu Management</h1>
  <div style="text-align:right;margin-bottom:1.2rem"><a href="admin_addmenu.php" class="add-btn"><i class="fas fa-plus"></i> Add Menu</a></div>
  <?php
    displayMenuSection($conn,1,120,'Malay Food');
    displayMenuSection($conn,2,120,'Western Food');
    displayMenuSection($conn,3,120,'Korean Food');
    displayMenuSection($conn,4,120,'Drinks');
  ?>
</main>
<!-- Modal -->
<div id="modalOverlay">
  <div class="modal">
    <span class="close-modal" onclick="hideModal()">&times;</span>
    <img id="modalImg" src="" alt="Menu Image" />
    <h3 id="modalName"></h3>
    <p class="food-price">RM <span id="modalPrice"></span></p>
    <p id="modalDesc"></p>
  </div>
</div>
<!-- Toast -->
<div id="toast"></div>
<script>
function showMenuDetail(img,name,price,desc){
  document.getElementById('modalImg').src='../images/'+img;
  document.getElementById('modalImg').alt=name;
  document.getElementById('modalName').textContent=name;
  document.getElementById('modalPrice').textContent=price;
  document.getElementById('modalDesc').textContent=desc;
  document.getElementById('modalOverlay').style.display='flex';
}
function hideModal(){document.getElementById('modalOverlay').style.display='none';}
// Toast util
function triggerToast(msg,color='#28a745'){const t=document.getElementById('toast');t.textContent=msg;t.style.background=color;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3000)}
// Handle query msg
(function(){const params=new URLSearchParams(window.location.search);const msg=params.get('msg');if(!msg)return;switch(msg){case 'add_success':triggerToast('Menu added successfully!');break;case 'add_fail':triggerToast('Failed to add menu!','#dc3545');break;case 'delete_success':triggerToast('Menu deleted successfully!');break;case 'delete_fail':triggerToast('Failed to delete menu!','#dc3545');break;case 'update_success':triggerToast('Menu updated successfully!');break;case 'update_fail':triggerToast('Failed to update menu!','#dc3545');break;}window.history.replaceState({},document.title,window.location.pathname);})();
</script>
</body>
</html>
