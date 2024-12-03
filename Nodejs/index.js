import express from "express";
import mongoose from "mongoose";

// Initialize Express
const app = express();
app.use(express.json()); // Middleware to parse JSON bodies

// Connection URI
const mongoUri = "mongodb://localhost:27017/codeigniter_mongodb"; // Replace 'yourDatabaseName' with your actual database name

// Function to connect to MongoDB
async function connectDB() {
    try {
        await mongoose.connect(mongoUri);
        console.log("MongoDB is connected");
    } catch (err) {
        console.error("Error while connecting to MongoDB", err);
    }
}
connectDB();

// Define User Schema
const empSchema = new mongoose.Schema({
    id:{type:Number,require:true},
    name: { type: String, required: true },
    email: { type: String, required: true },
    password: { type: String, required: true }
});
const User = mongoose.model('User', empSchema);

// Define Routes
app.post("/create", async (req, res) => {
    try {
        const userData = new User(req.body);
        const saveUser = await userData.save();
        res.status(200).json(saveUser);
    } catch (error) {
        res.status(500).json({ error: "internal server error" });
    }
});

app.delete("/delete/:id", async (req, res) => {
    try {
        const id = req.params.id;
        const userExist = await User.findOne({ id:id});
        console.log(id);
        if (!userExist) {
            return res.status(400).json({ message: "User Not exist" });
        }
        await User.findOneAndDelete({ id: id });
        res.status(201).json({ message: "user deleted successfully" });
    } catch (error) {
        return res.status(500).json({ message: "Internal server error" });
    }
});

app.put("/update/:id", async (req, res) => {
    try {
        const id = req.params.id;
        const userExist = await User.findOne({ id: id});
        if (!userExist) {
            return res.status(400).json({ message: "User does not exist" });
        }
        const updateUser = await User.findOneAndUpdate({ id: id  }, req.body, { new: true });
        res.status(201).json(updateUser);
    } catch (error) {
        res.status(500).json({ error: "Internal server error" });
    }
});


// Test Route
app.get('/get', (req, res) => {
    res.send("hello from nodejs ");
});

// Start Server
app.listen(4000, () => {
    console.log("server is running on 4000");
});
