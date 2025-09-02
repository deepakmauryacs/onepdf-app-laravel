<style>
  /* Fixed footer variant */
  .sticky-footer.is-fixed{
    position: fixed;
    bottom: 0;
    right: 0;
    left: 14rem;                 /* SB Admin 2 default sidebar width */
    z-index: 1030;
    border-top: 1px solid #e5e7eb;
    background: #fff;
  }

  /* When body has sidebar toggled (collapsed) OR on small screens, take full width */
  body.sidebar-toggled .sticky-footer.is-fixed,
  @media (max-width: 768px){
    .sticky-footer.is-fixed{ left: 0; }
  }

  /* Avoid content hiding behind fixed footer */
  #content-wrapper{ padding-bottom: 60px; }  /* footer height approx */
</style>

    <!-- Footer -->
    <footer class="sticky-footer bg-white is-fixed">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Your Website <?php echo date('Y'); ?></span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="bi bi-arrow-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="../logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->

<script src="<?php echo $assets_path; ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?php echo $assets_path; ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?php echo $assets_path; ?>js/sb-admin-2.min.js"></script>



</body>
</html>
