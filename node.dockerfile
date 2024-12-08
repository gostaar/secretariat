# Utilisation de l'image officielle Node.js
FROM node:alpine

WORKDIR /var/www/html
# Copier les fichiers package.json et package-lock.json dans le container
COPY package*.json ./

# Installer les dépendances npm
RUN npm install

# Copier le reste de l'application
COPY . .

RUN npm update && npm run dev
# Commande pour démarrer l'application (si nécessaire)
CMD ["npm", "start"]