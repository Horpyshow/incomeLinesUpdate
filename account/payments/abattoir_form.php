<div class="min-h-96">
  <form method="post" id="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="space-y-6">
    <fieldset>
      <!-- Hidden fields -->
      <input type="hidden" name="posting_officer_id" value="<?php echo $staff['user_id']; ?>" />
      <input type="hidden" name="posting_officer_name" value="<?php echo $staff['full_name']; ?>">
      <input type="hidden" name="income_line" value="<?php echo $income_line; ?>">
      <input type="hidden" name="posting_officer_dept" value="<?php echo $menu['department']; ?>">

      <div class="bg-white shadow rounded-lg p-6">
        <?php include 'payments/remittance_form_inc.php'; ?>

        <!-- Transaction Description -->
        <div class="mb-4">
          <label for="transaction_descr" class="block text-sm font-medium text-gray-700">Transaction Description</label>
          <div class="flex mt-1">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
              <i class="glyphicon glyphicon-list-alt"></i>
            </span>
            <input 
              type="text" 
              name="transaction_descr" 
              id="transaction_descr"
              class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
              placeholder="Transaction description" 
              value="<?php if (isset($_POST['transaction_descr']) || isset($income_line_desc)) echo @$transaction_descr; ?>" 
              pattern=".{6,}" 
              onBlur="loadCalc()" 
              readonly 
            />
          </div>
        </div>

        <!-- Category -->
        <div class="mb-4">
          <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
          <div class="flex mt-1">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
              <i class="glyphicon glyphicon-list-alt"></i>
            </span>
            <select 
              name="category" 
              id="category" 
              class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
              onBlur="loadCalc()" 
              required
            >
              <option value="">Select a category</option>
              <option value="Cows Killed">Cows Killed</option>
              <option value="Cows Takeaway">Cows Takeaway</option>
              <option value="Goats Killed">Goats Killed</option>
              <option value="Goats Takeaway">Goats Takeaway</option>
              <option value="Pots of Pomo">Pots of Pomo</option>
            </select>
          </div>
        </div>

        <!-- Quantity -->
        <div class="mb-4">
          <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
          <div class="flex mt-1">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
              <i class="glyphicon glyphicon-list-alt"></i>
            </span>
            <input 
              type="text" 
              id="quantity" 
              name="quantity" 
              class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
              value="<?php if (isset($_POST['quantity'])) echo @$quantity; ?>" 
              maxlength="4" 
              onBlur="loadCalc()" 
              required
            />
          </div>
        </div>

        <!-- Receipt No -->
        <div class="mb-4">
          <label for="receipt_no" class="block text-sm font-medium text-gray-700">Receipt No</label>
          <div class="flex mt-1">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
              <i class="glyphicon glyphicon-tag"></i>
            </span>
            <input 
              type="text" 
              name="receipt_no" 
              id="receipt_no"
              class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
              placeholder="Receipt No" 
              pattern="^\d{7}$" 
              value="<?php if (isset($_POST['receipt_no'])) echo @$receipt_no; ?>" 
              maxlength="7" 
              onBlur="loadCalc()" 
              required
            />
          </div>
        </div>

        <!-- Amount Paid -->
        <div class="mb-4">
          <label for="amount_paid" class="block text-sm font-medium text-gray-700">Amount Remitted</label>
          <div class="flex mt-1">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">&#8358;</span>
            <input 
              type="text" 
              id="amount_paid" 
              name="amount_paid" 
              class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
              placeholder="Amount Remitted" 
              value="<?php if (isset($_POST['amount_paid'])) echo @$amount_paid; ?>" 
              maxlength="20" 
              onBlur="loadCalc()" 
              readonly
            />
          </div>
        </div>

        <!-- Remitter's Name -->
        <div class="mb-4">
          <label for="remitting_staff" class="block text-sm font-medium text-gray-700">Remitter's Name</label>
          <div class="flex mt-1">
            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
              <i class="glyphicon glyphicon-user"></i>
            </span>
            <select 
              name="remitting_staff" 
              id="remitting_staff"
              class="flex-1 block w-full rounded-none rounded-r-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" 
              required
            >
              <option value="">Select...</option>
              <?php
              $query3 = "SELECT * FROM staffs WHERE department = 'Wealth Creation' ORDER BY full_name ASC ";
              $leasing_set = @mysqli_query($dbcon, $query3); 
              while ($leasing_officer = mysqli_fetch_array($leasing_set, MYSQLI_ASSOC)) {
                echo '<option value="'.$leasing_officer['user_id'].'-wc">'.$leasing_officer['full_name'].'</option>';
              }

              $query4 = "SELECT * FROM staffs_others ORDER BY full_name ASC ";
              $leasing_set2 = @mysqli_query($dbcon, $query4); 
              while ($leasing_officer2 = mysqli_fetch_array($leasing_set2, MYSQLI_ASSOC)) {
                echo '<option value="'.$leasing_officer2['id'].'-so">'.$leasing_officer2['full_name'].' - '.$leasing_officer2['department'].'</option>';
              }
              ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Submit button include -->
      <?php include 'payments/submit_button.php'; ?>  
    </fieldset>
  </form>
</div>
