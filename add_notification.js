const mongoose = require('mongoose');
require('dotenv').config(); // Charger les variables d'environnement
const app = express();
// Connexion à MongoDB
const connectDB = async () => {
  try {
    const uri = process.env.MONGO_URI || 'mongodb+srv://wahi2024:wahi2024@cluster0.3les9.mongodb.net/calin_bebe';
    await mongoose.connect(uri) 
    console.log('MongoDB connecté');
  } catch (error) {
    console.error('Erreur de connexion à MongoDB:', error.message);
    process.exit(1); // Arrêter le processus si la connexion échoue
  }
};

// Schéma et modèle pour les notifications
const notificationSchema = new mongoose.Schema({
  message: { type: String, required: true },
  timestamp: { type: Date, default: Date.now },
});
const Notification = mongoose.model('Notification', notificationSchema);

// Fonction pour ajouter une notification
const addNotification = async (message) => {
  try {
    const notification = new Notification({ message });
    await notification.save();
    console.log('Notification ajoutée avec succès!');
  } catch (error) {
    console.error('Erreur lors de l\'ajout de la notification:', error.message);
  }
};

// Fonction pour récupérer les notifications les plus récentes
const getNotifications = async (limit = 5) => {
  try {
    const notifications = await Notification.find()
      .sort({ timestamp: -1 }) // Trier par date décroissante
      .limit(limit); // Limiter le nombre de notifications récupérées
    return notifications;
  } catch (error) {
    console.error('Erreur lors de la récupération des notifications:', error.message);
    return [];
  }
};

// Exemple d'utilisation pour ajouter et récupérer des notifications
const start = async () => {
  await connectDB();

  // Ajouter une notification
  await addNotification('Une nouvelle tâche a été ajoutée.');

  // Récupérer et afficher les dernières notifications
  const notifications = await getNotifications();
  console.log('Dernières notifications :');
  notifications.forEach((notification) => {
    console.log(`- ${notification.message} (${notification.timestamp})`);
  });

  // Fermer la connexion après l'exécution
  mongoose.connection.close();
};

start(); // Démarrer l'exécution



