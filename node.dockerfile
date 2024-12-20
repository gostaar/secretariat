FROM node:alpine

WORKDIR /var/www/html

COPY package*.json ./

RUN npm install

COPY . .

RUN npm update && npm run dev

CMD ["npm", "start"]