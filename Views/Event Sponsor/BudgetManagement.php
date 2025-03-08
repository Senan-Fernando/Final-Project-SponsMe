<?php
session_start();
include '../../Model/db.php';

// Check if the user is logged in
if (!isset($_SESSION['userEmail'])) {
    header('Location: ../Login.php');
    exit();
}

$userEmail = $_SESSION['userEmail'];

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

try {
    // Fetch sponsor details
    $sql = "SELECT `id`, `company_name`, `unit`, `company_registration_code`, `emp_id`, `company_status` 
            FROM `sponsors` WHERE `email` = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $sponsor = $result->fetch_assoc();
    } else {
        echo "<script>alert('Sponsor data not found! Redirecting to login.'); window.location.href = '../Login.php';</script>";
        exit();
    }
    
    // Get single budget entry for editing
    $editId = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
    $editEntry = null;
    
    if ($editId > 0) {
        $editSql = "SELECT * FROM `sponsorship_budget` WHERE `id` = ? AND `company_registration_code` = ?";
        $editStmt = $conn->prepare($editSql);
        
        if ($editStmt === false) {
            throw new Exception("Prepare failed for edit query: " . $conn->error);
        }
        
        $editStmt->bind_param("is", $editId, $sponsor['company_registration_code']);
        $editStmt->execute();
        $editResult = $editStmt->get_result();
        
        if ($editResult->num_rows > 0) {
            $editEntry = $editResult->fetch_assoc();
        }
    }
    
    // Process form submission for updating entry
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_entry']) && $sponsor['company_status'] === 'Main') {
        $entryId = isset($_POST['entry_id']) ? intval($_POST['entry_id']) : 0;
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $type = isset($_POST['transaction_type']) ? $_POST['transaction_type'] : 'Expense';
        $date = isset($_POST['transaction_date']) ? $_POST['transaction_date'] : date('Y-m-d');
        
        if ($amount > 0 && $entryId > 0) {
            $updateSql = "UPDATE `sponsorship_budget` 
                          SET `amount` = ?, `description` = ?, `transaction_type` = ?, `transaction_date` = ? 
                          WHERE `id` = ? AND `company_registration_code` = ?";
            
            $updateStmt = $conn->prepare($updateSql);
            
            if ($updateStmt === false) {
                throw new Exception("Prepare failed for update: " . $conn->error);
            }
            
            $updateStmt->bind_param("dssssi", 
                $amount,
                $description,
                $type,
                $date,
                $entryId,
                $sponsor['company_registration_code']
            );
            
            if ($updateStmt->execute()) {
                echo "<script>alert('Budget entry updated successfully!'); window.location.href = 'BudgetManagement.php';</script>";
                exit();
            } else {
                echo "<script>alert('Error updating budget entry: " . $updateStmt->error . "');</script>";
            }
            
            $updateStmt->close();
        } else {
            echo "<script>alert('Invalid entry data!');</script>";
        }
    }
    
    // Process form submission for adding new entry
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_entry']) && $sponsor['company_status'] === 'Main') {
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $type = isset($_POST['transaction_type']) ? $_POST['transaction_type'] : 'Expense';
        $date = isset($_POST['transaction_date']) ? $_POST['transaction_date'] : date('Y-m-d');
        
        if ($amount > 0) {
            $insertSql = "INSERT INTO `sponsorship_budget` 
                          (`sponsor_id`, `emp_id`, `unit`, `company_registration_code`, `amount`, 
                           `description`, `transaction_type`, `transaction_date`) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $insertStmt = $conn->prepare($insertSql);
            
            if ($insertStmt === false) {
                throw new Exception("Prepare failed for insert: " . $conn->error);
            }
            
            $insertStmt->bind_param("isssdsss", 
                $sponsor['id'], 
                $sponsor['emp_id'], 
                $sponsor['unit'], 
                $sponsor['company_registration_code'],
                $amount,
                $description,
                $type,
                $date
            );
            
            if ($insertStmt->execute()) {
                echo "<script>alert('Budget entry added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding budget entry: " . $insertStmt->error . "');</script>";
            }
            
            $insertStmt->close();
        } else {
            echo "<script>alert('Amount must be greater than zero!');</script>";
        }
    }
    
    // Process delete request
    if (isset($_GET['delete']) && $sponsor['company_status'] === 'Main') {
        $deleteId = intval($_GET['delete']);
        
        $deleteSql = "DELETE FROM `sponsorship_budget` WHERE `id` = ? AND `company_registration_code` = ?";
        $deleteStmt = $conn->prepare($deleteSql);
        
        if ($deleteStmt === false) {
            throw new Exception("Prepare failed for delete: " . $conn->error);
        }
        
        $deleteStmt->bind_param("is", $deleteId, $sponsor['company_registration_code']);
        
        if ($deleteStmt->execute()) {
            echo "<script>alert('Budget entry deleted successfully!'); window.location.href = 'BudgetManagement.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error deleting budget entry: " . $deleteStmt->error . "');</script>";
        }
        
        $deleteStmt->close();
    }
    
    // Fetch budget entries based on company_registration_code
    $budgetSql = "SELECT * FROM `sponsorship_budget` 
                  WHERE `company_registration_code` = ? 
                  ORDER BY `transaction_date` DESC";
    
    $budgetStmt = $conn->prepare($budgetSql);
    
    if ($budgetStmt === false) {
        throw new Exception("Prepare failed for budget query: " . $conn->error);
    }
    
    $budgetStmt->bind_param("s", $sponsor['company_registration_code']);
    $budgetStmt->execute();
    $budgetResult = $budgetStmt->get_result();
    
    // Calculate total budget
    $totalSql = "SELECT 
                    SUM(CASE WHEN transaction_type = 'Income' THEN amount ELSE 0 END) as total_income,
                    SUM(CASE WHEN transaction_type = 'Expense' THEN amount ELSE 0 END) as total_expense
                 FROM `sponsorship_budget` 
                 WHERE `company_registration_code` = ?";
    
    $totalStmt = $conn->prepare($totalSql);
    
    if ($totalStmt === false) {
        throw new Exception("Prepare failed for total calculation: " . $conn->error);
    }
    
    $totalStmt->bind_param("s", $sponsor['company_registration_code']);
    $totalStmt->execute();
    $totalResult = $totalStmt->get_result();
    $totals = $totalResult->fetch_assoc();
    
    $totalIncome = $totals['total_income'] ?? 0;
    $totalExpense = $totals['total_expense'] ?? 0;
    $currentBudget = $totalIncome - $totalExpense;
    
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sponsorship Budget Management</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-500 to-gray-400 min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar -->
    <div class="bg-blue-900 text-white w-full md:w-1/4 p-6 flex flex-col items-center">
        <h2 class="text-2xl font-bold mb-6 text-center">SponsMe</h2>
        <nav class="flex flex-col gap-4 w-full">
            <a href="../home.php" class="bg-blue-700 p-3 rounded text-center">Home</a>
            <a href="Profile.php" class="bg-blue-700 p-3 rounded text-center">Profile</a>
            <a href="Request.php" class="bg-blue-700 p-3 rounded text-center">Sponsorship Requests</a>
            <a href="#" class="bg-blue-700 p-3 rounded text-center">Sponsorship Budget Management</a>
        </nav>
        <div class="mt-auto w-full">
            <a href="../login.php" class="bg-blue-700 p-3 rounded text-center block mt-6">Log Out</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-4 md:p-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Sponsorship Budget Management</h3>
            
            <!-- Budget Summary Card -->
            <div class="bg-blue-50 p-4 rounded-lg shadow mb-6">
                <h4 class="text-lg font-semibold text-blue-800 mb-2">Budget Summary</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-3 rounded shadow">
                        <div class="text-green-600 font-medium">Total Income</div>
                        <div class="text-2xl font-bold"><?php echo number_format($totalIncome, 2); ?></div>
                    </div>
                    <div class="bg-white p-3 rounded shadow">
                        <div class="text-red-600 font-medium">Total Expenses</div>
                        <div class="text-2xl font-bold"><?php echo number_format($totalExpense, 2); ?></div>
                    </div>
                    <div class="bg-white p-3 rounded shadow">
                        <div class="text-blue-800 font-medium">Current Budget</div>
                        <div class="text-2xl font-bold <?php echo $currentBudget >= 0 ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo number_format($currentBudget, 2); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Company Information -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Company Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded">
                    <div>
                        <p class="text-gray-600">Company Name: <span class="font-medium"><?php echo htmlspecialchars($sponsor['company_name']); ?></span></p>
                        <p class="text-gray-600">Unit: <span class="font-medium"><?php echo htmlspecialchars($sponsor['unit']); ?></span></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Registration Code: <span class="font-medium"><?php echo htmlspecialchars($sponsor['company_registration_code']); ?></span></p>
                        <p class="text-gray-600">Status: <span class="font-medium <?php echo $sponsor['company_status'] === 'Main' ? 'text-green-600' : 'text-blue-600'; ?>">
                            <?php echo htmlspecialchars($sponsor['company_status']); ?>
                        </span></p>
                    </div>
                </div>
            </div>

            <?php if ($editEntry): ?>
            <!-- Edit Budget Entry Form -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Edit Budget Entry</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="bg-gray-50 p-4 rounded">
                    <input type="hidden" name="entry_id" value="<?php echo $editEntry['id']; ?>">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Amount</label>
                            <input type="number" name="amount" step="0.01" min="0.01" class="w-full p-3 border border-gray-300 rounded" 
                                   value="<?php echo htmlspecialchars($editEntry['amount']); ?>" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Transaction Type</label>
                            <select name="transaction_type" class="w-full p-3 border border-gray-300 rounded" required>
                                <option value="Income" <?php echo $editEntry['transaction_type'] === 'Income' ? 'selected' : ''; ?>>Income</option>
                                <option value="Expense" <?php echo $editEntry['transaction_type'] === 'Expense' ? 'selected' : ''; ?>>Expense</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Date</label>
                            <input type="date" name="transaction_date" class="w-full p-3 border border-gray-300 rounded" 
                                   value="<?php echo htmlspecialchars($editEntry['transaction_date']); ?>" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Description</label>
                            <input type="text" name="description" class="w-full p-3 border border-gray-300 rounded" 
                                   value="<?php echo htmlspecialchars($editEntry['description']); ?>" required>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-3">
                        <button type="submit" name="update_entry" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            Update Entry
                        </button>
                        <a href="BudgetManagement.php" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded inline-block">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
            <?php elseif ($sponsor['company_status'] === 'Main'): ?>
            <!-- Budget Entry Form (Only for Main status) -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Add Budget Entry</h4>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="bg-gray-50 p-4 rounded">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Amount</label>
                            <input type="number" name="amount" step="0.01" min="0.01" class="w-full p-3 border border-gray-300 rounded" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Transaction Type</label>
                            <select name="transaction_type" class="w-full p-3 border border-gray-300 rounded" required>
                                <option value="Income">Income</option>
                                <option value="Expense">Expense</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Date</label>
                            <input type="date" name="transaction_date" class="w-full p-3 border border-gray-300 rounded" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Description</label>
                            <input type="text" name="description" class="w-full p-3 border border-gray-300 rounded" required>
                        </div>
                    </div>
                    <button type="submit" name="add_entry" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                        Add Entry
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="mb-6 bg-yellow-50 p-4 rounded border border-yellow-200">
                <p class="text-yellow-800">
                    <strong>Note:</strong> Only sponsors with 'Main' status can add budget entries. 
                    You can view the budget details, but cannot make changes.
                </p>
            </div>
            <?php endif; ?>

            <!-- Budget History Table -->
            <div>
                <h4 class="text-lg font-semibold text-gray-800 mb-2">Budget History</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 rounded">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">Date</th>
                                <th class="py-2 px-4 border-b text-left">Description</th>
                                <th class="py-2 px-4 border-b text-left">Type</th>
                                <th class="py-2 px-4 border-b text-right">Amount</th>
                                <th class="py-2 px-4 border-b text-left">Added By</th>
                                <?php if ($sponsor['company_status'] === 'Main'): ?>
                                <th class="py-2 px-4 border-b text-center">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($budgetResult->num_rows > 0): ?>
                                <?php while ($row = $budgetResult->fetch_assoc()): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 border-b">
                                            <?php echo htmlspecialchars(date('M d, Y', strtotime($row['transaction_date']))); ?>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <?php echo htmlspecialchars($row['description']); ?>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="px-2 py-1 rounded text-sm <?php echo $row['transaction_type'] === 'Income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                <?php echo htmlspecialchars($row['transaction_type']); ?>
                                            </span>
                                        </td>
                                        <td class="py-2 px-4 border-b text-right font-medium <?php echo $row['transaction_type'] === 'Income' ? 'text-green-600' : 'text-red-600'; ?>">
                                            <?php echo number_format($row['amount'], 2); ?>
                                        </td>
                                        <td class="py-2 px-4 border-b">
                                            <span class="text-sm">
                                                Unit: <?php echo htmlspecialchars($row['unit']); ?><br>
                                                Emp ID: <?php echo htmlspecialchars($row['emp_id']); ?>
                                            </span>
                                        </td>
                                        <?php if ($sponsor['company_status'] === 'Main'): ?>
                                        <td class="py-2 px-4 border-b text-center">
                                            <a href="?edit=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                                <span class="inline-block hover:underline">Edit</span>
                                            </a>
                                            <a href="?delete=<?php echo $row['id']; ?>" class="text-red-600 hover:text-red-800" 
                                               onclick="return confirm('Are you sure you want to delete this entry?');">
                                                <span class="inline-block hover:underline">Delete</span>
                                            </a>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?php echo $sponsor['company_status'] === 'Main' ? '6' : '5'; ?>" class="py-4 px-4 text-center text-gray-500">No budget entries found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for additional interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional JavaScript functionality here
        });
    </script>
</body>
</html>