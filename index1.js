var mongoose=require("mongoose");
const express =require("express");
const router=express.Router()
const app=express();
const bodyParser=require("body-parser")
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ "extended": false }));
var uri="mongodb+srv://95mishraankur:sskkiitt1243@cluster0.9d7te.mongodb.net/sample_mflix?authSource=admin&replicaSet=atlas-6byi82-shard-0&readPreference=primary&appname=MongoDB%20Compass&ssl=true";
mongoose.connect(uri,{useUnifiedTopology:true,useNewUrlParser:true});
const connection=mongoose.connection;
connection.once("open",function(){
    console.log("mongoo connected")
});
const sensorSchema= new mongoose.Schema({
    uptime:Number,
    temperature:Number,
    humidity:Number
})
const Sensorion=mongoose.model("Sensor",sensorSchema);
 getSensor();
async function getSensor(){
    const Sensor=await Sensorion.find();
    console.log(Sensor)
}
router.get("/", async (req, res) => {
	const posts = await Sensorion.find()
	res.send(posts)
})


app.use('/', router);

app.listen(4000);