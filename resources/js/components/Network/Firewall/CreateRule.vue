<template>
    <!-- Modal -->
    <div class="modal fade text-left" id="create-rule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Create Rule</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="#">
                    <div class="modal-body">
                        <div class="form-group">
                            <label id="rule_name">Rule Name: </label>
                            <input type="text" id="rule_name" placeholder="Rule Name" class="form-control" />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="source">Source: </label>
                                    <select class="form-control" id="source">
                                        <option value="Test">Test</option>
                                        <option selected value="Source IP">Source IP</option>
                                        <option value="Test3">Option3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label id="protocol">Protocol: </label>
                                    <select class="form-control" id="protocol">
                                        <option value="Test">Test</option>
                                        <option selected value="Protocol">Protocol</option>
                                        <option value="Test3">Option3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label id="interface">Interface: </label>
                                    <select class="form-control" id="interface">
                                        <option value="Test">Test</option>
                                        <option selected value="Interface">Interface</option>
                                        <option value="Test3">Option3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label id="destination_ip">Destination IP: </label>
                                    <input type="text" id="destination_ip" placeholder="Destination IP" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label id="destination_port">Destination Port: </label>
                                    <input type="text" id="destination_port" placeholder="Destination Port" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label id="organization_name">Organization Name: </label>
                                    <input type="text" id="organization_name" placeholder="Organization Name" class="form-control" />
                                </div>
                            </div>
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