	(function() {
	  function formatCurrency(value) {
	    return `$${value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
	  }

	  function formatThousands(str) {
	    const digits = str.replace(/[^0-9]/g, '');
	    if (!digits) return '';
	    return parseInt(digits, 10).toLocaleString('en-US');
	  }

	  function parseInput(str) {
	    return parseFloat(String(str).replace(/,/g, ''));
	  }

	  function findInContainer(container, selector, fallbackSelector) {
	    return container.querySelector(selector) || (fallbackSelector ? container.querySelector(fallbackSelector) : null);
	  }

	  function initCalculator(container) {
	    const amountInput = findInContainer(container, '[data-field="amount"]', '#amount');
	    const rateInput = findInContainer(container, '[data-field="rate"]', '#rate');
	    const monthsInput = findInContainer(container, '[data-field="months"]', '#months');
	    const calculateBtn = findInContainer(container, '[data-action="calculate"]', '#calculate-btn');
	    const monthlyPaymentEl = findInContainer(container, '[data-output="monthly-payment"]', '#monthly-payment');
	    const totalInterestEl = findInContainer(container, '[data-output="total-interest"]', '#total-interest');
	    const totalPaymentEl = findInContainer(container, '[data-output="total-payment"]', '#total-payment');
	    const tableBody = findInContainer(container, '[data-table="amortization"] tbody', '#amortization-table tbody');

	    if (!amountInput || !rateInput || !monthsInput || !calculateBtn || !monthlyPaymentEl || !totalInterestEl || !totalPaymentEl || !tableBody) {
	      return;
	    }

	    amountInput.addEventListener('input', function() {
	      const pos = this.selectionStart;
	      const prevLen = this.value.length;
	      this.value = formatThousands(this.value);
	      const diff = this.value.length - prevLen;
	      this.setSelectionRange(pos + diff, pos + diff);
	    });

	    calculateBtn.addEventListener('click', function() {
	      const principal = parseInput(amountInput.value);
	      const annualRateRaw = parseFloat(rateInput.value);
      const months = parseInt(parseInput(monthsInput.value), 10);

      if (isNaN(principal) || isNaN(annualRateRaw) || isNaN(months) || principal <= 0 || annualRateRaw < 0 || months <= 0) {
        alert('Por favor ingresa valores válidos.');
        return;
      }

      const annualRate = annualRateRaw / 100;
	      const monthlyRate = annualRate / 12;

	      let monthlyPayment;
	      if (monthlyRate === 0) {
	        monthlyPayment = principal / months;
	      } else {
	        monthlyPayment = principal * (monthlyRate * Math.pow(1 + monthlyRate, months)) / (Math.pow(1 + monthlyRate, months) - 1);
	      }

	      const totalPayment = monthlyPayment * months;
	      const totalInterest = totalPayment - principal;

	      monthlyPaymentEl.textContent = formatCurrency(monthlyPayment);
	      totalInterestEl.textContent = formatCurrency(totalInterest);
	      totalPaymentEl.textContent = formatCurrency(totalPayment);

	      tableBody.innerHTML = '';

	      let balance = principal;
	      for (let i = 1; i <= 12 && balance > 0; i++) {
	        const interest = balance * monthlyRate;
	        const capital = monthlyPayment - interest;
	        balance = Math.max(0, balance - capital);

	        const row = document.createElement('tr');
	        row.innerHTML = `
		<td>${i}</td>
		<td>${formatCurrency(monthlyPayment)}</td>
		<td>${formatCurrency(capital)}</td>
		<td>${formatCurrency(interest)}</td>
		<td>${formatCurrency(balance)}</td>
	      `;
	        tableBody.appendChild(row);
	      }
	    });
	  }

	  document.addEventListener('DOMContentLoaded', function() {
	    const calculators = document.querySelectorAll('.loan-calculator-container');
	    calculators.forEach(initCalculator);
	  });
	})();

