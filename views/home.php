<div id="app" class="container">
	<div class="form form-horizontal" style="border: 1px solid lightgrey; border-radius: 5px; background-color: #F3F3F3; padding: 5px">
		<input name="action" type="hidden" value="get" />
		<div class="form-body">
			<div class="form-group">
				<label class="col-md-2 control-label">Código</label>
				<div class="col-md-6">
					<input name="code" v-model="code" class="form-control" type="text" />
				</div>
			</div>
		</div>
		<br>
		<div class="form-actions">
			<div class="row">
				<div class="col-md-offset-2 col-md-10">
					<button id="find" @click='buscarItem' class="btn btn-primary">Encontrar</button>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div>
		<h3>{{ nameItem }}</h3>
		<table class="table" v-if="detail">
			<tbody>
				<tr v-for="(data,index) in detail">
					<td>{{data.name_item_types}}</td>
					<td v-if="data.name_items">{{data.name_items}}</td>
					<td v-else>
						<select class="form-select" @change="cambiarValor($event,index,data.TypeOptions,data.quantity)">
							<option v-for="(to,i) in data.TypeOptions" :value="i">{{to.name_items}}</option>
						</select>
					</td>
					<td v-if="data.name_items" :ref="`tdEditable${index}`">{{round(data.price)}} X {{ data.quantity }} {{data.unit}}</td>
					<td v-else :ref="`tdEditable${index}`">{{ round(data.TypeOptions[0]?.price) }} X {{ data.quantity }} {{ data.TypeOptions[0]?.unit}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="row" style="font-weight:bold; text-size:125%; padding:10px">
		<div class="col-md-10" style="text-align:right">
			TOTAL: {{ round(total) }}
		</div>
		<div class="col-md-2 total" style="text-align:right">
		</div>
	</div>
</div>
<script>
	const {
		createApp
	} = Vue;
	createApp({
		data() {
			return {
				code: 'AQ10100',
				nameItem: '',
				detail: [],
				total: 0
			}
		},
		methods: {
			buscarItem: async function() {
				this.total = 0;
				this.detail = [];
				this.nameItem = '';
				try {
					const service = await axios.post('?action=buscarItem', {
						code: this.code
					});
					this.nameItem = service.data.record.name;
					this.detail = service.data.details;
					this.totalF(service.data.details);
				} catch (error) {
					console.log(error);
				}

			},
			round: function(num) {
				return Math.round(num * 100) / 100
			},
			cambiarValor: function(event, index, TypeOptions, quantity) {              
				let posi = event.target.value;
				let valor = `${this.round(TypeOptions[posi]?.price)} X ${quantity} ${TypeOptions[posi]?.unit}`;
				this.$refs[`tdEditable${index}`][0].innerText = valor;
				this.detail[index].TypeOptions.forEach(element => {
					// console.log(element);
					element.Active = false;                                                        
				});
				this.detail[index].TypeOptions[posi].Active = true;
				console.log(this.detail[index].TypeOptions[posi]);
				this.totalF(this.detail);
			},
			totalF: function(data) {
				let cont = 0;
				data.forEach((element, posi) => {
					if (element.name_items) {
						cont += element.price * element.quantity
					} else {
						element.TypeOptions.forEach( e => {
							if(e.Active){
								console.log(e.name_items);
								cont += e.price * element.quantity
							}
						});
					}
				});
				this.total = cont;
			}
		}
	}).mount('#app');
</script>


