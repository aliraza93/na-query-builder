<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="add-url" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add URL</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <label>URL Name: </label>
                        <div class="form-group">
                            <input type="text" placeholder="URL Name" class="form-control" />
                        </div>

                        <label>Block Page: </label>
                        <div class="form-group">
                            <select class="form-control">
                                <option value="Test">Test</option>
                                <option value="Test2">Test2</option>
                                <option value="Test3">Option3</option>
                            </select>
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