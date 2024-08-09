import clsx from "clsx";
import { Row, Col } from "antd";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Card from "../Card/Card";
import { Loading } from "../Loading";
import { SubHeading } from "../SubHeading";
import { Container } from "../../Container";

export default function CardContentSix({ indexItem, background }) {
    const config = usePage().props.components[indexItem];

    console.log(config);

    return (
        <Suspense fallback={<Loading />}>
            <div
                className="cardContentSix backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <SubHeading>{config.title}</SubHeading>
                    <Row
                        gutter={[
                            {
                                xxl: 20,
                                xl: 15,
                                lg: 30,
                                md: 15,
                                sm: 15,
                                xs: 15,
                            },
                            { xs: 10, sm: 20, md: 40, lg: 60, xl: 30 },
                        ]}
                        className={clsx("cardContentSix--row")}
                    >
                        {config.components?.length > 0 &&
                            config.components?.map((item, index) => (
                                <Col
                                    xxl={4}
                                    xl={4}
                                    md={6}
                                    sm={8}
                                    xs={12}
                                    key={`${item.title} + ${
                                        item.img
                                    } + ${index} + ${Math.random()}`}
                                    className="custom-card-col"
                                >
                                    <Card
                                        className="cardContentSix__card"
                                        ellipsis={8}
                                        config={config.components[index]}
                                    />
                                </Col>
                            ))}
                    </Row>
                </Container>
            </div>
        </Suspense>
    );
}
