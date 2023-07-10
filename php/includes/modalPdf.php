<!-- modal para visualizar resultado -->
<input type="hidden" name="modalMostrar" id="modalMostrar" value="">
<div class="modal fade" id="modalPdfGenerado" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body">
                <iframe src="" id="iframePdf" style="width:100%; height:500px;" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success text-uppercase" id="btnNotificarComprobante">Notificar Documento</button>
                <button type="button" class="btn btn-danger text-uppercase" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- modal para visualizar resultado -->