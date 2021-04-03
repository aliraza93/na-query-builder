<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-subnet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Create Subnet</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <label>Subnet Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Subnet Name" class="form-control" />
                        </div>

                        <label>Description: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Description" class="form-control" />
                        </div>

                        <label>IP Address From: </label>
                        <div class="form-group">
                            <input type="text" placeholder="IP Address From" class="form-control" />
                        </div>

                        <label>IP Address To: </label>
                        <div class="form-group">
                            <input type="text" placeholder="IP Address To" class="form-control" />
                        </div>

                        <label>IP Address Points: </label>
                        <div class="form-group">
                            <input type="text" placeholder="IP Address Points" class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    
}
</script>