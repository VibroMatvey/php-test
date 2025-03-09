// Get references to DOM elements
const submit_btn = document.getElementById("submit");
const user_field = document.getElementById("user");
const data_container = document.getElementById("data");
const data_table_article = data_container.querySelector('h2');
const data_table = data_container.querySelector("table");
const data_table_tbody = data_table.querySelector("tbody");
let date = new Date();

// Assign an event handler to the submit button
submit_btn.onclick = form_submit

function form_submit(e) {
  e.preventDefault();
  const user_id = user_field.value
  const user_name = user_field.querySelector("option:checked").textContent;

  // Fetch user data from the server
  fetch(`/?route=data&user=${user_id}`)
    .then(res => res.json())
    .then(data => {
      if (!data || !Array.isArray(data)) {
        return
      }
      date = new Date();

      data_table_article.textContent = `Transactions of ${user_name}`
      data_table.append(data_table_tbody);
      data_table_tbody.innerHTML = '';

      // Create a table row for each transaction
      data.forEach(transaction => {
        const row = create_row_table(transaction)
        data_table_tbody.append(row);
      });

      data_container.style.display = "block";
    })
    .catch(err => alert(`Error fetching data: ${err}`));
}

function create_row_table(transaction) {
  const row = document.createElement("tr");

  date.setMonth(transaction.month - 1)
  const month_name = date.toLocaleString('en-US', {month: 'long'});

  const month_cell = document.createElement("td");
  month_cell.textContent = month_name;
  row.append(month_cell);

  const amount_cell = document.createElement("td");
  amount_cell.textContent = transaction.amount;
  row.append(amount_cell);

  const count_cell = document.createElement("td");
  count_cell.textContent = transaction.count;
  row.append(count_cell);

  return row
}
