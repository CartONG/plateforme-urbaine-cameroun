FROM node:lts-alpine AS vue_base

WORKDIR /app

# persistent / runtime deps
RUN apk add --no-cache \
    	fish \
	;

ARG VITE_DOMAIN
ENV VITE_DOMAIN=$VITE_DOMAIN

COPY package.json yarn.lock ./
RUN yarn

COPY . .

EXPOSE 3000

# for Development environnement
FROM vue_base AS vue_dev

CMD [ "yarn", "dev", "--port", "3000", "--host", "0.0.0.0" ]

# for Production environnement
FROM vue_base AS vue_prod

ARG VITE_SENTRY_DSN
ENV VITE_SENTRY_DSN=$VITE_SENTRY_DSN

RUN yarn build
