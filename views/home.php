<div id="app" class="container">
    <div class="form form-horizontal" style="border: 1px solid lightgrey; border-radius: 5px; background-color: #F3F3F3; padding: 5px">
        <input name="action" type="hidden" value="get" />
        <div class="form-body">
            <div class="form-group">
                <label class="col-md-2 control-label">Código</label>
                <div class="col-md-6">
                    <input name="code" data-bind="value: code" class="form-control" type="text" />
                </div>
            </div>
        </div>
        <br>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-offset-2 col-md-10">
                    <button id="find" data-bind="click: buscarItem" class="btn btn-primary">Encontrar</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div>
        <h3 data-bind="text: nameItem"></h3>
        <table class="table" data-bind="if: detail">
            <tbody data-bind="foreach: detail">
                <tr>
                    <td data-bind="text: name_item_types"></td>
                    <td >                        
                        <div data-bind="if: name_items">
                            <span data-bind="text: name_items"></span>
                        </div>
                        <div data-bind="ifnot: name_items">
                            <select data-bind="options: TypeOptions, optionsText: 'name_items', optionsValue: 'ConcatData', event: { change: function(data, event) { $root.cambiarValor(event,$index(),quantity); } }" class="form-select">                                                            
                            </select>                                                                                         
                        </div>
                    </td>                   
                    <td>
                        <div data-bind="if: name_items" >
                            <span data-bind="text: $root.round(price) + ' X ' + quantity + ' ' + unit, attr: { id:'tdEditable' + $index() } "></span>                             
                        </div>
                        <div data-bind="ifnot: name_items" >
                            <span data-bind="text: $root.round(TypeOptions[0]?.price) + ' X ' + quantity + ' ' + TypeOptions[0]?.unit, attr: { id:'tdEditable' + $index() } "></span>  
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row" style="font-weight:bold; text-size:125%; padding:10px">
        <div class="col-md-10" style="text-align:right" data-bind="text: 'TOTAL ' + total()">            
        </div>
        <div class="col-md-2 total" style="text-align:right">
        </div>
    </div>
</div>
<script>
    var ViewModel = function() {
        this.code = ko.observable('AQ10100');
        this.nameItem = ko.observable('');
        this.detail = ko.observable([]);
        
        this.buscarItem = async () => {            
            this.detail([]);
            this.nameItem('');
            try {
                const service = await axios.post('?action=buscarItem', {
                    code: this.code()
                });
                this.nameItem(service.data.record.name);
                this.detail(service.data.details);                                
            } catch (error) {
                console.log(error);
            }
        }

        this.round = (num) => {
            return Math.round(num * 100) / 100
        }

        this.cambiarValor = (event, index, quantity) => {            
            let posi = event.target.value;            
            let valor = ''            
            this.detail()[index].TypeOptions.forEach(element => {
                if(element.ConcatData == posi){
                    element.Active = true;     
                    valor = `${this.round(element?.price)} X ${quantity} ${element?.unit}`;            
                }else{
                    element.Active = false;     
                }
            });
            document.getElementById(`tdEditable${index}`).innerHTML = valor;  
            this.detail(this.detail())                  
        }

        this.total = ko.computed(() => {            
            let cont = 0;
            this.detail().forEach((element, posi) => {
                if (element.name_items) {
                    cont += element.price * element.quantity
                } else {
                    element.TypeOptions.forEach(e => {
                        if (e.Active) {                            
                            cont += e.price * element.quantity
                        }
                    });
                }
            });
            return this.round(cont);
        }, this);
    };

    ko.applyBindings(new ViewModel());
</script>