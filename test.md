signoz-access-token:272085dc-0763-4ddb-b2b5-5bcebf896698
https://ingest.in.signoz.cloud:443

env OTEL_PHP_AUTOLOAD_ENABLED=true \
    OTEL_SERVICE_NAME=nanu \
    OTEL_TRACES_EXPORTER=otlp \
    OTEL_METRICS_EXPORTER=none \
    OTEL_LOGS_EXPORTER=none \
    OTEL_EXPORTER_OTLP_PROTOCOL=grpc \
    OTEL_EXPORTER_OTLP_ENDPOINT=https://ingest.in.signoz.cloud:443 \
          OTEL_EXPORTER_OTLP_HEADERS=signoz-access-token=272085dc-0763-4ddb-b2b5-5bcebf896698 \
    OTEL_PROPAGATORS=baggage,tracecontext \
    php -S localhost:8080