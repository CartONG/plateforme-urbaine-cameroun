# 🚀 Deployement

## How to deploy features on DEV and UAT environnement

A continuous integration is setup using **Github Action**

Pushing on the `uat` branch, will automatically deploy on `UAT` environment. \
Pushing on any other branch than `main` will deploy to `DEV` environment. 

::: info
More information on the Continuous integration [here](./ci.md)
:::

## Deploy to Production

To deploy on prod :

1. Merge your `uat` to `main` branch
2. Create [a new release version](https://github.com/CartONG/plateforme-urbaine-cameroun/releases/new)
And create a new tag on `main` branch with the release version following `v0.0.0` format

3. On the production server create a backup
``` bash
sudo make create-backup
```
This command backups database, media files and qgis projects. (this can take several minutes)

4. Checkout your branch (replace with the appropriate tag)
``` bash
git fetch origin
git checkout tags/<replace-with-your-tag>
```

5. Deploy it
``` bash
make deploy-prod
```

6. Check all is good, and website is reachable. Yes ? Congrats !

Issue, and unwanted data loss ? You can proceed with the next step.

## Pull backup

::: danger
This operation will erase data, and will go to a previous state. Be careful if you use it
:::

Go back to your previous version targeting a specific tag. And then pull the backup and redeploy.

``` bash
git checkout tags/<replace-with-the-tag-you-want-to-revert-to>
make pull-backup tag=<replace-with-the-tag-you-want-to-revert-to>
make deploy-prod
```
