function deltask (id) { if (confirm("Delete task?")) {
  document.getElementById("ninID").value = id;
  document.getElementById("ninForm").submit();
}}