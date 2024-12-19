<div class="p-6">
    <div class="flex justify-between gap-8 p-4">
        <!-- Transaction History Section (Left) -->
        <div class="bg-white rounded-lg p-6 w-full">
            <h3 class="text-2xl font-bold text-blue-800 mb-4">Wallet Transactions</h3>
            <div class="space-y-4" id="transaction-history"></div>

            <!-- Pagination Controls -->
            <div class="flex justify-between mt-4">
                <button id="prev" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition duration-200" disabled>Previous</button>
                <button id="next" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition duration-200">Next</button>
            </div>
        </div>

        <!-- Wallet Balance Section (Right) -->
        <div class="bg-blue-800 rounded-lg p-4 h-auto max-h-[200px] w-2/3">
            <h3 class="text-2xl font-bold text-white mb-4">Wallet Balance</h3>
            <div class="flex items-center justify-between mb-4">
                <div class="text-left">
                    <p id="balance" class="text-4xl font-bold text-white">$240</p>
                    <p class="text-sm text-white">Available Balance</p>
                </div>
            </div>
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition duration-200">Add Funds</button>
        </div>
    </div>

    <!-- Withdrawal Section -->
    <div class="mt-6">
        <h3 class="text-xl font-bold text-blue-800 mb-4">Withdraw Funds</h3>
        <div class="flex items-center gap-4">
            <input type="number" id="withdraw-amount" placeholder="Enter amount" class="p-2 border rounded-lg w-1/2" />
            <button id="withdraw-btn" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-500 transition duration-200">Withdraw</button>
        </div>
        <p id="withdraw-error" class="text-red-500 mt-2 hidden">Insufficient funds or invalid amount</p>
    </div>
</div>

<script>
    let balance = 240;  // initial balance
    const transactions = [
        { amount: 50, description: 'Added Funds', date: '2024-11-01' },
        { amount: -20, description: 'Withdrawal', date: '2024-11-02' },
        { amount: 30, description: 'Added Funds', date: '2024-11-03' },
        { amount: -40, description: 'Withdrawal', date: '2024-11-04' },
        { amount: 60, description: 'Added Funds', date: '2024-11-05' },
        { amount: -25, description: 'Withdrawal', date: '2024-11-06' },
        { amount: 15, description: 'Added Funds', date: '2024-11-07' },
        { amount: -75, description: 'Withdrawal', date: '2024-11-08' },
    ];

    const itemsPerPage = 5;
    let currentPage = 1;

    const balanceElement = document.getElementById('balance');
    const transactionHistoryElement = document.getElementById('transaction-history');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');
    const withdrawButton = document.getElementById('withdraw-btn');
    const withdrawAmountInput = document.getElementById('withdraw-amount');
    const withdrawError = document.getElementById('withdraw-error');

    function renderTransactions(page) {
        transactionHistoryElement.innerHTML = '';

        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const currentTransactions = transactions.slice(start, end);

        currentTransactions.forEach(transaction => {
            const transactionElement = document.createElement('div');
            transactionElement.classList.add('bg-gray-100', 'rounded-lg', 'p-4', 'flex', 'justify-between', 'items-center');

            // Add styling based on transaction type (deposit or withdrawal)
            const amountClass = transaction.amount > 0 ? 'text-green-500' : 'text-red-500'; // Green for deposits, Red for withdrawals
            transactionElement.innerHTML = `
                <div class="flex items-center justify-between w-full">
                  <div>
                    <p class="text-lg font-semibold text-gray-800">${transaction.description}</p>
                    <p class="text-sm ${amountClass}">${transaction.amount > 0 ? `+ $${transaction.amount}` : `- $${Math.abs(transaction.amount)}`}</p>
                  </div>
                    <p class="text-xs text-gray-400">${transaction.date}</p>
                </div>
            `;
            transactionHistoryElement.appendChild(transactionElement);
        });

        prevButton.disabled = page === 1;
        nextButton.disabled = page * itemsPerPage >= transactions.length;
    }

    function updateBalance(amount) {
        balance += amount;
        balanceElement.textContent = `$${balance}`;
    }

    // Handle next and previous buttons
    prevButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            renderTransactions(currentPage);
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentPage * itemsPerPage < transactions.length) {
            currentPage++;
            renderTransactions(currentPage);
        }
    });

    // Handle withdrawal
    withdrawButton.addEventListener('click', () => {
        const withdrawAmount = parseFloat(withdrawAmountInput.value);

        if (isNaN(withdrawAmount) || withdrawAmount <= 0) {
            return;
        }

        if (withdrawAmount > balance) {
            withdrawError.classList.remove('hidden');
        } else {
            withdrawError.classList.add('hidden');
            transactions.push({ amount: -withdrawAmount, description: 'Withdrawal', date: new Date().toLocaleDateString() });
            updateBalance(-withdrawAmount);
            renderTransactions(currentPage);  // Refresh transaction history
            withdrawAmountInput.value = '';  // Clear input
        }
    });

    // Initial render
    renderTransactions(currentPage);
</script>
