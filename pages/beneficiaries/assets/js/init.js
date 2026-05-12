/**
 * Bootstraps the beneficiaries page after all scripts are loaded.
 */

document.addEventListener('DOMContentLoaded', async () => {
  // 1. Fetch first page from the server (no filters applied yet)
  await fetchBeneficiaries({ page: 1, limit: pageSize });

  // 2. Populate filter dropdowns from the data that came back
  await initBeneficiaryFilters();

  // 3. Render the table with what we have
  renderTable();
});