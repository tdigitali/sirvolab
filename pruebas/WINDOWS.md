# SIRVOLAB: entorno de pruebas en Windows

Levanta en la computadora del desarrollador el **sistema original**
(PHP 5.6) y el **sistema migrado** (PHP 8.3) lado a lado, con datos
inventados. Es el mismo entorno del servidor de pruebas, pero corriendo
dentro de **WSL2** (Linux integrado en Windows) con **Podman** sin root.

No necesita Docker Desktop ni XAMPP.

## Requisitos de la computadora

| | Mínimo | Recomendado |
|---|---|---|
| Windows | 10 versión 22H2 (64 bits) | 11 |
| Procesador | 4 núcleos, con virtualización activada | 6 núcleos o más |
| Memoria RAM | 8 GB | 16 GB |
| Disco libre en C: | 25 GB | 40 GB, de preferencia SSD |

Además:
- **Permisos de administrador**, solo para instalar WSL la primera vez.
- **Virtualización activada en el BIOS/UEFI.** Para revisarlo: Administrador
  de tareas → Rendimiento → CPU → "Virtualización: Habilitado". Si dice
  Deshabilitado, hay que activarla en el BIOS (Intel VT-x o AMD-V/SVM).
- **Internet** la primera vez, para descargar Ubuntu, las imágenes base y
  las dependencias (unos 3 GB). Si la red de la oficina usa proxy o bloquea
  sitios, deben estar permitidos: `docker.io`, `registry-1.docker.io`,
  `deb.debian.org`, `archive.debian.org`, `repo.packagist.org`, `github.com`
  y `codeload.github.com`.
- Los puertos **8080, 8081 y 8025** deben estar libres.

## 1. Instalar WSL con Ubuntu (una sola vez)

Abra **PowerShell como administrador** y ejecute:

```powershell
wsl --install -d Ubuntu-24.04
```

Reinicie la computadora si se lo pide. Al abrir "Ubuntu 24.04" desde el
menú Inicio, le pedirá crear un usuario y contraseña de Linux. No tienen que
ser los de Windows.

## 2. Instalar Podman dentro de Ubuntu (una sola vez)

En la ventana de **Ubuntu**:

```bash
sudo apt update && sudo apt install -y podman podman-compose unzip
```

## 3. Copiar el paquete y descomprimirlo

**Importante:** descomprima el paquete **dentro de Linux** (su carpeta
`~`), no en `C:\` ni en `/mnt/c/...`. Desde las carpetas de Windows es muy
lento y los scripts pierden sus permisos.

Si el .zip quedó en Descargas de Windows, en Ubuntu ejecute (cambie
`SuUsuario` por su usuario de Windows):

```bash
cp /mnt/c/Users/SuUsuario/Downloads/sirvolab-pruebas.zip ~ && cd ~ && unzip -q sirvolab-pruebas.zip
```

## 4. Levantar el entorno

```bash
cd ~/sirvolab/pruebas && ./iniciar.sh
```

La primera vez tarda entre 10 y 20 minutos: descarga imágenes y compila
PHP. Al terminar muestra las direcciones y los usuarios.

## 5. Abrir los sistemas

Desde el navegador de **Windows**, directo y sin túnel:

| Dirección | Qué es |
|---|---|
| http://localhost:8080 | Sistema original (PHP 5.6) |
| http://localhost:8081 | Sistema migrado (PHP 8.3) |
| http://localhost:8025 | Mailpit: todos los correos que envían los sistemas |

Usuario `admin@pruebas.test`, contraseña `Pruebas2026!`. El resto de los
usuarios está en `README.md`.

## Uso diario

En Ubuntu, dentro de `~/sirvolab/pruebas`:

| Para… | Comando |
|---|---|
| Volver a levantar, sin reconstruir | `./iniciar.sh --sin-construir` |
| Probar cambios en el código | `./iniciar.sh` (reconstruye las imágenes) |
| Empezar de cero con datos nuevos | `./iniciar.sh --reiniciar-datos` |
| Ver errores del sistema migrado | `podman-compose logs -f nuevo` |
| Apagar todo | `podman-compose down` |

Al reiniciar Windows, abra Ubuntu y ejecute `./iniciar.sh --sin-construir`.

Para editar el código, use VS Code con la extensión **WSL**: desde Ubuntu,
`cd ~/sirvolab && code .`. Los cambios se ven al reconstruir con
`./iniciar.sh`.

## Si algo falla

- **`./iniciar.sh: Permission denied`**: ejecútelo como `bash iniciar.sh`.
- **Avisos de "cgroup" o "systemd user session"**: revise que
  `/etc/wsl.conf` tenga estas dos líneas:

  ```
  [boot]
  systemd=true
  ```

  Luego, en PowerShell, ejecute `wsl --shutdown` y vuelva a abrir Ubuntu.
- **El navegador no abre `localhost:8081`**: ejecute `wsl --shutdown` en
  PowerShell, abra Ubuntu, corra `./iniciar.sh --sin-construir` y vuelva a
  intentar. Revise también que ningún otro programa (Tomcat, IIS, XAMPP)
  ocupe los puertos 8080, 8081 o 8025.
- **Se queda sin memoria o va muy lento**: WSL usa por defecto la mitad de
  la RAM. Para darle más, cree `C:\Users\SuUsuario\.wslconfig` con:

  ```
  [wsl2]
  memory=8GB
  ```

  y ejecute `wsl --shutdown`.
- **Falla la descarga de dependencias**: casi siempre es el proxy o el
  firewall de la red (ver Requisitos). Pruebe desde otra red para
  confirmarlo.

El detalle de cada error queda en `~/sirvolab/pruebas/.iniciar.log`.
