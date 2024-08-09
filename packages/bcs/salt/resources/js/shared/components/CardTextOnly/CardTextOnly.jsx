import { Typography } from "antd";
import React, { Suspense } from "react";

import { Button } from "../Button";
import { SubHeading } from "../SubHeading";
import { Container } from "../../Container";

const { Paragraph } = Typography;

export default function CardTextOnly({ config }) {
    return (
        <Suspense fallback={<h1>Loading CardTextOnly ...</h1>}>
            <div id={config.id} className="cardTextOnly">
                <Container>
                    <div className="flex flex-col items-center">
                        {config.title && (
                            <SubHeading>{config.title}</SubHeading>
                        )}
                        {config.description && (
                            <Paragraph className="cardTextOnly__description">
                                <div
                                    dangerouslySetInnerHTML={{
                                        __html: config.description,
                                    }}
                                    className="description"
                                />
                            </Paragraph>
                        )}
                        {(config.config_button?.icon?.data ||
                            config.config_button?.text) && (
                            <Button
                                config={config.config_button}
                                className="card--info--button"
                            />
                        )}
                    </div>
                </Container>
            </div>
        </Suspense>
    );
}
