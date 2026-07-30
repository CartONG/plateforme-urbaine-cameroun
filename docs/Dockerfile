
FROM node:lts-alpine AS docs_base

WORKDIR /app
COPY package.json yarn.lock ./
RUN yarn
COPY . .
EXPOSE 5000

# for Development environnement
FROM docs_base AS docs_dev

CMD ["yarn", "docs:dev"]

# for Production environnement
FROM docs_base AS docs_prod

RUN yarn docs:build && mkdir -p /app/.vitepress/dist