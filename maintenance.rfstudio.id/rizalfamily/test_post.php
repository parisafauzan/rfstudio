<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>File Manager</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f6f8fb;
      padding: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
    }

    th {
      font-weight: bold;
      color: #555;
    }

    tr {
      background-color: #fff;
      border-radius: 8px;
      margin-bottom: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    td {
      border-bottom: 1px solid #eee;
      vertical-align: middle;
    }

    .file-icon i {
      font-size: 18px;
    }

    .file-name {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .options {
      display: flex;
      gap: 10px;
    }

    .option-button {
      border: none;
      background: none;
      color: #555;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .option-button:hover {
      color: #007bff;
    }
  </style>
</head>
<body>

<h2>File Manager</h2>

<table>
  <thead>
    <tr>
      <th>Type</th>
      <th>File Name</th>
      <th>Uploaded</th>
      <th>Size</th>
      <th>Options</th>
    </tr>
  </thead>
  <tbody id="fileTableBody">
    <!-- Data akan dimasukkan lewat JavaScript -->
  </tbody>
</table>

<script>
  const files = [
    { name: "Gambar-Pemandangan-Sunset.jpg", type: "image", uploaded: "2025-05-06", size: "2.1 MB" },
    { name: "Video-Tutorial.mp4", type: "video", uploaded: "2025-05-04", size: "14.7 MB" },
    { name: "Data-Keuangan.xlsx", type: "sheet", uploaded: "2025-05-01", size: "500 KB" },
    { name: "Berkas-PDF-Laporan.pdf", type: "pdf", uploaded: "2025-04-29", size: "1.2 MB" },
    { name: "Folder-Project", type: "folder", uploaded: "2025-05-03", size: "—" },
  ];

  const icons = {
    image: "fa-file-image",
    video: "fa-file-video",
    sheet: "fa-file-excel",
    pdf: "fa-file-pdf",
    folder: "fa-folder",
    default: "fa-file"
  };

  const tbody = document.getElementById("fileTableBody");

  files.forEach(file => {
    const tr = document.createElement("tr");

    const iconTd = document.createElement("td");
    iconTd.className = "file-icon";
    iconTd.innerHTML = `<i class="fa-solid ${icons[file.type] || icons.default}"></i>`;
    tr.appendChild(iconTd);

    const nameTd = document.createElement("td");
    nameTd.className = "file-name";
    nameTd.title = file.name;
    nameTd.textContent = file.name;
    tr.appendChild(nameTd);

    const uploadTd = document.createElement("td");
    uploadTd.textContent = file.uploaded;
    tr.appendChild(uploadTd);

    const sizeTd = document.createElement("td");
    sizeTd.textContent = file.size;
    tr.appendChild(sizeTd);

    const optionsTd = document.createElement("td");
    optionsTd.className = "options";

    if (file.type !== "folder") {
      const previewBtn = document.createElement("button");
      previewBtn.className = "option-button";
      previewBtn.innerHTML = `<i class="fa-solid fa-eye"></i> Preview`;
      previewBtn.onclick = () => alert(`Preview ${file.name}`);
      optionsTd.appendChild(previewBtn);
    }

    const downloadBtn = document.createElement("button");
    downloadBtn.className = "option-button";
    downloadBtn.innerHTML = `<i class="fa-solid fa-download"></i> Download`;
    downloadBtn.onclick = () => alert(`Download ${file.name}`);
    optionsTd.appendChild(downloadBtn);

    tr.appendChild(optionsTd);
    tbody.appendChild(tr);
  });
</script>

</body>
</html>
