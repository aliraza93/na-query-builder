<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-scope" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add Scope</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <label>Scope Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="Scope Name" class="form-control" />
                        </div>

                        <div class="custom-control custom-checkbox"> 
                            <input class="custom-control-input" type="checkbox" value="" id="checkboxSelectAll">
                            <label class="custom-control-label" for="checkboxSelectAll">Enforced</label>
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