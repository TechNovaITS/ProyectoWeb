FROM oraclelinux:9

# Instala el repositorio de MySQL y su clave actual para obtener MySQL Shell oficial.
# Shell se ejecuta como tarea independiente, así los nodos solo contienen el servidor.
RUN dnf install -y \
      https://dev.mysql.com/get/mysql80-community-release-el9-1.noarch.rpm \
    && curl -fsSLo /etc/pki/rpm-gpg/RPM-GPG-KEY-mysql-2025 \
      https://repo.mysql.com/RPM-GPG-KEY-mysql-2025 \
    && sed -i 's/RPM-GPG-KEY-mysql-2022/RPM-GPG-KEY-mysql-2025/g' \
      /etc/yum.repos.d/mysql-community*.repo \
    && dnf install -y mysql-shell \
    && dnf clean all
