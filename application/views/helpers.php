Fecha con icono calendario
            <div class="form-group">
                <label class="col-sm-2 control-label">Fecha</label>
                <div class='col-sm-9'>
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' id="start" class="form-control"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                </div>
            </div>

                    $this->db->where("first_name LIKE '%$search%'");
                    $this->db->where("first_name LIKE '" . $this->db->escape_like_str($search) . "%'");