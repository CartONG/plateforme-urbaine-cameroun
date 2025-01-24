# 🚀 Setup - Local environment

Here is what you need to setup your local environment.
## Requirements

- [**Docker**](https://docs.docker.com/desktop/) with compose plugin

*If you are using **Windows** :*
- [**WSL 2**](https://docs.docker.com/desktop/wsl/#turn-on-docker-desktop-wsl-2)
- [**VSCode WSL extension**](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl)

## Getting started

To setup your local env project (Only the first time)

``` bash
# Clone your project
# ⚠️ On Windows make sure you are cloning it from your WSL environnement
git clone git@github.com:CartONG/plateforme-urbaine-cameroun.git puc && cd puc

# Build and pull your docker images 🐋 & setup your hosts local domains
make init
```

## Run your project

Once done, just run

``` bash
make dev
```

Happy coding ! 🚀