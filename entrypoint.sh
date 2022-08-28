#!/bin/sh
# run:
# ./manage-etc-hosts.sh add 10.20.1.2 test.com
# ./manage-etc-hosts.sh remove 10.20.1.2 test.com

# PATH TO YOUR HOSTS FILE
ETC_HOSTS=/etc/hosts


remove() {
    # IP to add/remove.
    IP=$1
    # Hostname to add/remove.
    HOSTNAME=$2
    HOSTS_LINE="$IP[[:space:]]$HOSTNAME"
    if [ -n "$(grep -P $HOSTS_LINE $ETC_HOSTS)" ]
    then
        echo "$HOSTS_LINE Found in your $ETC_HOSTS, Removing now...";
        sudo sed -i".bak" "/$HOSTS_LINE/d" $ETC_HOSTS
    else
        echo "$HOSTS_LINE was not found in your $ETC_HOSTS";
    fi
}

add() {
    IP=$1
    HOSTNAME=$2
    HOSTS_LINE="$IP[[:space:]]$HOSTNAME"
    line_content=$( printf "%s\t%s\n" "$IP" "$HOSTNAME" )
    if [ -n "$(grep -P $HOSTS_LINE $ETC_HOSTS)" ]
        then
            echo "$line_content already exists : $(grep $HOSTNAME $ETC_HOSTS)"
        else
            echo "Adding $line_content to your $ETC_HOSTS";
            sudo -- sh -c -e "echo '$line_content' >> /etc/hosts";

            if [ -n "$(grep -P $HOSTNAME $ETC_HOSTS)" ]
                then
                    echo "$line_content was added succesfully";
                else
                    echo "Failed to Add $line_content, Try again!";
            fi
    fi
}

$@

{ # try
    echo "Criando rede docker externa"
    sudo docker network create --gateway 172.26.0.1 --subnet 172.26.0.0/16 empresta-network 2>/dev/null
} || { # catch
    # save log for exception
    echo "Rede ja existe, seguindo...."
}

{ # try
    echo "Criando volume docker externo"
    sudo docker volume create --name=empresta-dbdata 2>/dev/null
    sudo docker volume create --name=empresta-redis-data 2>/dev/null
    sudo docker volume create --name=empresta-certs 2>/dev/null
    sudo docker volume create --name=empresta-esdata 2>/dev/null
    sudo docker volume create --name=empresta-kibana 2>/dev/null
    sudo docker volume create --name=empresta-portainer 2>/dev/null
} || { # catch
    # save log for exception
    echo "Volume ja existe, seguindo...."
}

add 127.0.0.1 apiempresta.local.com.br


