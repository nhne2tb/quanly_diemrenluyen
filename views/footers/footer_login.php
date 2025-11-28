<!-- FOOTER -->
 <?php 
 // views/footers/footer_login.php
 ?>
<style>
/* FOOTER ĐẸP – KHÔNG NHẢY */
.footer {
  text-align: center;
  color: #555;
  font-size: 0.9rem;
  padding: 20px 0;
  background: #fff;
  border-top: 1px solid #ddd;
  box-shadow: 0 -2px 6px rgba(0,0,0,0.05);

  /* Quan trọng: chống nhảy */
  position: relative !important;
  bottom: 0;
  width: 100%;
  margin-top: 40px;
}


</style>

<footer class="footer">
  © <?= date('Y') ?> Khoa Sư phạm Toán – Tin, Trường Đại học Đồng Tháp – Phát triển bởi Nguyễn Hồ Ninh Em (Sinh viên DTHU)
</footer>

<!-- SCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showTab(tabId) {
  const tabs = ['tab1', 'tab2'];
  tabs.forEach(id => {
    const el = document.getElementById(id);
    const btn = document.getElementById('btn' + id.charAt(0).toUpperCase() + id.slice(1));
    if (el && btn) {
      const active = (id === tabId);
      el.style.display = active ? 'block' : 'none';
      el.classList.toggle('show', active);
      btn.classList.toggle('active', active);
    }
  });
}
</script>

</body>
</html>
